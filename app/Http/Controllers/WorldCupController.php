<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\FootballMatch;
use App\Models\Prediction;
use App\Models\PredictionLog;
use App\Models\Prize;
use App\Models\ScoringRule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WorldCupController extends Controller
{
    /**
     * صفحه اصلی پیش‌بینی جام جهانی برای Blade.
     */
    public function index(): View
    {
        $campaign = Campaign::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $user = Auth::user();

        $upcomingMatches = FootballMatch::query()
            ->with(['stage', 'homeTeam', 'awayTeam'])
            ->when($campaign, fn ($query) => $query->where('campaign_id', $campaign->id))
            ->whereIn('status', ['scheduled', 'live'])
            ->where(function ($query) {
                $query->whereNull('match_date')->orWhere('match_date', '>=', now()->subHours(3));
            })
            ->orderBy('match_date')
            ->get();

        $pastMatches = FootballMatch::query()
            ->with([
                'stage',
                'homeTeam',
                'awayTeam',
                'predictions' => fn ($query) => $user
                    ? $query->where('user_id', $user->id)
                    : $query->whereRaw('1 = 0'),
            ])
            ->when($campaign, fn ($query) => $query->where('campaign_id', $campaign->id))
            ->where('status', 'finished')
            ->latest('match_date')
            ->limit(20)
            ->get();

        $myPredictions = collect();

        if ($user) {
            $myPredictions = Prediction::query()
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('match_id');
        }

        $leaderboard = User::query()
            ->select([
                'users.id',
                'users.name',
                'users.mobile',
                'users.avatar',
                DB::raw('COALESCE(SUM(predictions.points), 0) as total_points'),
                DB::raw("SUM(CASE WHEN predictions.result_type = 'exact' THEN 1 ELSE 0 END) as exact_count"),
            ])
            ->leftJoin('predictions', 'predictions.user_id', '=', 'users.id')
            ->where('users.is_active', true)
            ->groupBy('users.id', 'users.name', 'users.mobile', 'users.avatar')
            ->orderByDesc('total_points')
            ->orderByDesc('exact_count')
            ->limit(20)
            ->get();

        $myRank = null;
        $myStats = null;

        if ($user) {
            $rankedUsers = User::query()
                ->select([
                    'users.id',
                    DB::raw('COALESCE(SUM(predictions.points), 0) as total_points'),
                    DB::raw("SUM(CASE WHEN predictions.result_type = 'exact' THEN 1 ELSE 0 END) as exact_count"),
                ])
                ->leftJoin('predictions', 'predictions.user_id', '=', 'users.id')
                ->where('users.is_active', true)
                ->groupBy('users.id')
                ->orderByDesc('total_points')
                ->orderByDesc('exact_count')
                ->get();

            $myRank = $rankedUsers->search(fn ($row) => (int) $row->id === (int) $user->id);
            $myRank = $myRank === false ? null : $myRank + 1;

            $myStats = $rankedUsers->firstWhere('id', $user->id);
        }

        $scoringRules = ScoringRule::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('points')
            ->get();

        $prizes = Prize::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('rank_from')
            ->get();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('worldcup.index', compact(
            'campaign',
            'upcomingMatches',
            'pastMatches',
            'myPredictions',
            'leaderboard',
            'myRank',
            'myStats',
            'scoringRules',
            'prizes',
            'faqs'
        ));
    }

    /**
     * ثبت یا ویرایش پیش‌بینی تا قبل از شروع بازی.
     */
    public function submitPrediction(Request $request, FootballMatch $match): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'برای ثبت پیش‌بینی ابتدا وارد حساب کاربری شوید.');
        }

        $validated = $request->validate([
            'predicted_home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'predicted_away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ], [
            'predicted_home_score.required' => 'گل تیم اول را وارد کنید.',
            'predicted_away_score.required' => 'گل تیم دوم را وارد کنید.',
        ]);

        if ($match->status !== 'scheduled') {
            return back()->with('error', 'برای این بازی امکان ثبت یا ویرایش پیش‌بینی وجود ندارد.');
        }

        if ($match->prediction_deadline && now()->greaterThanOrEqualTo($match->prediction_deadline)) {
            return back()->with('error', 'مهلت ثبت پیش‌بینی این بازی تمام شده است.');
        }

        DB::transaction(function () use ($user, $match, $validated) {
            $prediction = Prediction::query()
                ->where('user_id', $user->id)
                ->where('match_id', $match->id)
                ->lockForUpdate()
                ->first();

            $oldHomeScore = $prediction?->predicted_home_score;
            $oldAwayScore = $prediction?->predicted_away_score;

            if (! $prediction) {
                $prediction = new Prediction();
                $prediction->user_id = $user->id;
                $prediction->match_id = $match->id;
                $prediction->result_type = 'pending';
                $prediction->points = 0;
            }

            $prediction->predicted_home_score = $validated['predicted_home_score'];
            $prediction->predicted_away_score = $validated['predicted_away_score'];
            $prediction->save();

            if ($oldHomeScore !== null || $oldAwayScore !== null) {
                PredictionLog::query()->create([
                    'prediction_id' => $prediction->id,
                    'user_id' => $user->id,
                    'match_id' => $match->id,
                    'old_home_score' => $oldHomeScore,
                    'old_away_score' => $oldAwayScore,
                    'new_home_score' => $prediction->predicted_home_score,
                    'new_away_score' => $prediction->predicted_away_score,
                ]);
            }
        });

        return back()->with('success', 'پیش‌بینی شما با موفقیت ثبت شد.');
    }
}
