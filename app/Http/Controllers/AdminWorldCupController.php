<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\FootballMatch;
use App\Models\Prediction;
use App\Models\Prize;
use App\Models\ScoringRule;
use App\Models\Stage;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminWorldCupController extends Controller
{
    /**
     * داشبورد ساده مدیریت مسابقه.
     */
    public function dashboard(): View
    {
        $activeCampaign = Campaign::query()->where('is_active', true)->latest('id')->first();

        $stats = [
            'users_count' => User::query()->where('is_active', true)->count(),
            'matches_count' => FootballMatch::query()->when($activeCampaign, fn ($q) => $q->where('campaign_id', $activeCampaign->id))->count(),
            'predictions_count' => Prediction::query()->count(),
            'finished_matches_count' => FootballMatch::query()->when($activeCampaign, fn ($q) => $q->where('campaign_id', $activeCampaign->id))->where('status', 'finished')->count(),
        ];

        $latestPredictions = Prediction::query()
            ->with(['user', 'match.homeTeam', 'match.awayTeam'])
            ->latest('id')
            ->limit(20)
            ->get();

        $leaderboard = $this->buildLeaderboardQuery()->limit(10)->get();

        return view('admin.worldcup.dashboard', compact('activeCampaign', 'stats', 'latestPredictions', 'leaderboard'));
    }

    /**
     * لیست بازی‌ها برای پنل مدیریت.
     */
    public function matches(Request $request): View
    {
        $status = $request->query('status');
        $campaignId = $request->query('campaign_id');

        $matches = FootballMatch::query()
            ->with(['campaign', 'stage', 'homeTeam', 'awayTeam'])
            ->when($campaignId, fn ($q) => $q->where('campaign_id', $campaignId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('match_date')
            ->paginate(30)
            ->withQueryString();

        $campaigns = Campaign::query()->latest('id')->get();

        return view('admin.worldcup.matches.index', compact('matches', 'campaigns', 'status', 'campaignId'));
    }

    /**
     * فرم ایجاد بازی.
     */
    public function createMatch(): View
    {
        return view('admin.worldcup.matches.create', [
            'campaigns' => Campaign::query()->orderByDesc('id')->get(),
            'stages' => Stage::query()->orderBy('sort_order')->get(),
            'teams' => Team::query()->orderBy('name_fa')->get(),
        ]);
    }

    /**
     * ذخیره بازی جدید.
     */
    public function storeMatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'stage_id' => ['required', 'exists:stages,id'],
            'home_team_id' => ['required', 'exists:teams,id', 'different:away_team_id'],
            'away_team_id' => ['required', 'exists:teams,id'],
            'match_date' => ['required', 'date'],
            'prediction_deadline' => ['nullable', 'date', 'before_or_equal:match_date'],
            'status' => ['required', Rule::in(['scheduled', 'live', 'finished', 'cancelled'])],
        ]);

        if (empty($validated['prediction_deadline'])) {
            $validated['prediction_deadline'] = $validated['match_date'];
        }

        FootballMatch::query()->create($validated);

        return redirect()->route('admin.worldcup.matches')->with('success', 'بازی جدید ثبت شد.');
    }

    /**
     * فرم ویرایش بازی.
     */
    public function editMatch(FootballMatch $match): View
    {
        $match->load(['campaign', 'stage', 'homeTeam', 'awayTeam']);

        return view('admin.worldcup.matches.edit', [
            'match' => $match,
            'campaigns' => Campaign::query()->orderByDesc('id')->get(),
            'stages' => Stage::query()->orderBy('sort_order')->get(),
            'teams' => Team::query()->orderBy('name_fa')->get(),
        ]);
    }

    /**
     * آپدیت اطلاعات بازی، بدون محاسبه امتیاز مگر status/result تغییر کند.
     */
    public function updateMatch(Request $request, FootballMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'stage_id' => ['required', 'exists:stages,id'],
            'home_team_id' => ['required', 'exists:teams,id', 'different:away_team_id'],
            'away_team_id' => ['required', 'exists:teams,id'],
            'match_date' => ['required', 'date'],
            'prediction_deadline' => ['nullable', 'date', 'before_or_equal:match_date'],
            'home_score' => ['nullable', 'integer', 'min:0', 'max:99'],
            'away_score' => ['nullable', 'integer', 'min:0', 'max:99'],
            'status' => ['required', Rule::in(['scheduled', 'live', 'finished', 'cancelled'])],
        ]);

        if (empty($validated['prediction_deadline'])) {
            $validated['prediction_deadline'] = $validated['match_date'];
        }

        DB::transaction(function () use ($match, $validated) {
            $match->update($validated);

            if ($match->status === 'finished' && $match->home_score !== null && $match->away_score !== null) {
                $this->calculatePredictionsForMatch($match->fresh());
                $this->refreshUserScores();
            }
        });

        return back()->with('success', 'اطلاعات بازی ذخیره شد.');
    }

    /**
     * ثبت نتیجه نهایی بازی و محاسبه امتیاز کاربران.
     */
    public function submitResult(Request $request, FootballMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        DB::transaction(function () use ($match, $validated) {
            $match->update([
                'home_score' => $validated['home_score'],
                'away_score' => $validated['away_score'],
                'status' => 'finished',
            ]);

            $this->calculatePredictionsForMatch($match->fresh());
            $this->refreshUserScores();
        });

        return back()->with('success', 'نتیجه بازی ثبت و امتیازها محاسبه شد.');
    }

    /**
     * محاسبه دوباره امتیازهای یک بازی.
     */
    public function recalculateMatch(FootballMatch $match): RedirectResponse
    {
        if ($match->status !== 'finished' || $match->home_score === null || $match->away_score === null) {
            return back()->with('error', 'برای محاسبه امتیاز، نتیجه نهایی بازی باید ثبت شده باشد.');
        }

        DB::transaction(function () use ($match) {
            $this->calculatePredictionsForMatch($match);
            $this->refreshUserScores();
        });

        return back()->with('success', 'امتیازهای این بازی دوباره محاسبه شد.');
    }

    /**
     * رده‌بندی کاربران.
     */
    public function leaderboard(): View
    {
        $leaderboard = $this->buildLeaderboardQuery()->paginate(50);

        return view('admin.worldcup.leaderboard', compact('leaderboard'));
    }

    /**
     * مدیریت قوانین امتیازدهی.
     */
    public function scoringRules(): View
    {
        $rules = ScoringRule::query()->orderBy('sort_order')->orderByDesc('points')->get();

        return view('admin.worldcup.scoring-rules.index', compact('rules'));
    }

    public function storeScoringRule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', 'unique:scoring_rules,key'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['required', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ScoringRule::query()->create($validated);

        return back()->with('success', 'قانون امتیازدهی ثبت شد.');
    }

    /**
     * مدیریت جوایز.
     */
    public function prizes(): View
    {
        $prizes = Prize::query()->orderBy('sort_order')->orderBy('rank_from')->get();

        return view('admin.worldcup.prizes.index', compact('prizes'));
    }

    public function storePrize(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rank_from' => ['required', 'integer', 'min:1'],
            'rank_to' => ['required', 'integer', 'min:1', 'gte:rank_from'],
            'title' => ['required', 'string', 'max:255'],
            'prize_amount' => ['required', 'numeric', 'min:0'],
            'prize_unit' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Prize::query()->create($validated);

        return back()->with('success', 'جایزه ثبت شد.');
    }

    /**
     * مدیریت FAQ.
     */
    public function faqs(): View
    {
        $faqs = Faq::query()->orderBy('sort_order')->get();

        return view('admin.worldcup.faqs.index', compact('faqs'));
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Faq::query()->create($validated);

        return back()->with('success', 'سوال متداول ثبت شد.');
    }

    /**
     * محاسبه امتیاز پیش‌بینی‌های یک بازی، داخل همین کنترلر طبق درخواست شما.
     */
    private function calculatePredictionsForMatch(FootballMatch $match): void
    {
        $match->loadMissing('predictions');

        $realHome = (int) $match->home_score;
        $realAway = (int) $match->away_score;
        $realDiff = $realHome - $realAway;
        $realWinner = $this->winnerSide($realHome, $realAway);

        $points = [
            'exact' => (int) (ScoringRule::query()->where('key', 'exact')->value('points') ?? 100),
            'winner_goal_diff' => (int) (ScoringRule::query()->where('key', 'winner_goal_diff')->value('points') ?? 50),
            'winner_only' => (int) (ScoringRule::query()->where('key', 'winner_only')->value('points') ?? 30),
            'wrong' => (int) (ScoringRule::query()->where('key', 'wrong')->value('points') ?? 10),
        ];

        Prediction::query()
            ->where('match_id', $match->id)
            ->chunkById(200, function ($predictions) use ($realHome, $realAway, $realDiff, $realWinner, $points) {
                foreach ($predictions as $prediction) {
                    $predHome = (int) $prediction->predicted_home_score;
                    $predAway = (int) $prediction->predicted_away_score;
                    $predDiff = $predHome - $predAway;
                    $predWinner = $this->winnerSide($predHome, $predAway);

                    if ($predHome === $realHome && $predAway === $realAway) {
                        $resultType = 'exact';
                    } elseif ($predWinner === $realWinner && $predDiff === $realDiff) {
                        $resultType = 'winner_goal_diff';
                    } elseif ($predWinner === $realWinner) {
                        $resultType = 'winner_only';
                    } else {
                        $resultType = 'wrong';
                    }

                    $prediction->update([
                        'result_type' => $resultType,
                        'points' => $points[$resultType],
                        'locked_at' => $prediction->locked_at ?? now(),
                        'calculated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * تعیین سمت برنده: home / away / draw.
     */
    private function winnerSide(int $homeScore, int $awayScore): string
    {
        if ($homeScore > $awayScore) {
            return 'home';
        }

        if ($awayScore > $homeScore) {
            return 'away';
        }

        return 'draw';
    }

    /**
     * آپدیت امتیاز خلاصه کاربران داخل users.
     */
    private function refreshUserScores(): void
    {
        User::query()
            ->select('users.id')
            ->chunkById(300, function ($users) {
                foreach ($users as $user) {
                    $summary = Prediction::query()
                        ->where('user_id', $user->id)
                        ->selectRaw('COALESCE(SUM(points), 0) as total_points')
                        ->selectRaw("SUM(CASE WHEN result_type = 'exact' THEN 1 ELSE 0 END) as exact_count")
                        ->first();

                    User::query()->where('id', $user->id)->update([
                        'total_score' => $summary->total_points ?? 0,
                        'exact_predictions_count' => $summary->exact_count ?? 0,
                    ]);
                }
            });
    }

    /**
     * Query مشترک رتبه‌بندی، اما بدون سرویس جداگانه.
     */
    private function buildLeaderboardQuery()
    {
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.mobile',
                'users.avatar',
                DB::raw('COALESCE(SUM(predictions.points), 0) as total_points'),
                DB::raw("SUM(CASE WHEN predictions.result_type = 'exact' THEN 1 ELSE 0 END) as exact_count"),
                DB::raw('COUNT(predictions.id) as predictions_count'),
            ])
            ->leftJoin('predictions', 'predictions.user_id', '=', 'users.id')
            ->where('users.is_active', true)
            ->groupBy('users.id', 'users.name', 'users.mobile', 'users.avatar')
            ->orderByDesc('total_points')
            ->orderByDesc('exact_count')
            ->orderBy('users.id');
    }
}
