<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorldCup2026Seeder extends Seeder
{
    private int $campaignId = 1;

    public function run(): void
    {
        DB::transaction(function () {
            $this->seedCampaign();
            $this->seedStages();
            $this->seedTeams();
            $this->seedScoringRules();
            $this->seedPrizes();
            $this->seedFaqs();
            $this->seedMatches();
        });
    }

    private function seedCampaign(): void
    {
        DB::table('campaigns')->updateOrInsert(
            ['id' => $this->campaignId],
            [
                'title' => 'پیش‌بینی جام جهانی رشت‌گلد',
                'subtitle' => 'پیش‌بینی کن و ۱۰ گرم طلا ببر',
                'start_at' => '2026-06-11 00:00:00',
                'end_at' => '2026-07-19 23:59:59',
                'total_prize_title' => '۱۰ گرم طلا',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function seedStages(): void
    {
        $stages = [
            ['id' => 1, 'title' => 'مرحله گروهی', 'sort_order' => 1],
            ['id' => 2, 'title' => 'یک‌شانزدهم نهایی', 'sort_order' => 2],
            ['id' => 3, 'title' => 'یک‌هشتم نهایی', 'sort_order' => 3],
            ['id' => 4, 'title' => 'ربع‌نهایی', 'sort_order' => 4],
            ['id' => 5, 'title' => 'نیمه‌نهایی', 'sort_order' => 5],
            ['id' => 6, 'title' => 'رده‌بندی', 'sort_order' => 6],
            ['id' => 7, 'title' => 'فینال', 'sort_order' => 7],
        ];

        foreach ($stages as $stage) {
            DB::table('stages')->updateOrInsert(['id' => $stage['id']], [
                'title' => $stage['title'],
                'sort_order' => $stage['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedTeams(): void
    {
        $teams = [
            ['id' => 1, 'name_fa' => 'مکزیک', 'name_en' => 'Mexico', 'fifa_code' => 'MEX', 'flag' => 'mx'],
            ['id' => 2, 'name_fa' => 'آفریقای جنوبی', 'name_en' => 'South Africa', 'fifa_code' => 'RSA', 'flag' => 'za'],
            ['id' => 3, 'name_fa' => 'کره جنوبی', 'name_en' => 'South Korea', 'fifa_code' => 'KOR', 'flag' => 'kr'],
            ['id' => 4, 'name_fa' => 'چک', 'name_en' => 'Czechia', 'fifa_code' => 'CZE', 'flag' => 'cz'],
            ['id' => 5, 'name_fa' => 'کانادا', 'name_en' => 'Canada', 'fifa_code' => 'CAN', 'flag' => 'ca'],
            ['id' => 6, 'name_fa' => 'بوسنی و هرزگوین', 'name_en' => 'Bosnia and Herzegovina', 'fifa_code' => 'BIH', 'flag' => 'ba'],
            ['id' => 7, 'name_fa' => 'قطر', 'name_en' => 'Qatar', 'fifa_code' => 'QAT', 'flag' => 'qa'],
            ['id' => 8, 'name_fa' => 'سوئیس', 'name_en' => 'Switzerland', 'fifa_code' => 'SUI', 'flag' => 'ch'],
            ['id' => 9, 'name_fa' => 'برزیل', 'name_en' => 'Brazil', 'fifa_code' => 'BRA', 'flag' => 'br'],
            ['id' => 10, 'name_fa' => 'مراکش', 'name_en' => 'Morocco', 'fifa_code' => 'MAR', 'flag' => 'ma'],
            ['id' => 11, 'name_fa' => 'هائیتی', 'name_en' => 'Haiti', 'fifa_code' => 'HAI', 'flag' => 'ht'],
            ['id' => 12, 'name_fa' => 'اسکاتلند', 'name_en' => 'Scotland', 'fifa_code' => 'SCO', 'flag' => 'gb-sct'],
            ['id' => 13, 'name_fa' => 'ایالات متحده', 'name_en' => 'United States', 'fifa_code' => 'USA', 'flag' => 'us'],
            ['id' => 14, 'name_fa' => 'پاراگوئه', 'name_en' => 'Paraguay', 'fifa_code' => 'PAR', 'flag' => 'py'],
            ['id' => 15, 'name_fa' => 'استرالیا', 'name_en' => 'Australia', 'fifa_code' => 'AUS', 'flag' => 'au'],
            ['id' => 16, 'name_fa' => 'ترکیه', 'name_en' => 'Turkey', 'fifa_code' => 'TUR', 'flag' => 'tr'],
            ['id' => 17, 'name_fa' => 'آلمان', 'name_en' => 'Germany', 'fifa_code' => 'GER', 'flag' => 'de'],
            ['id' => 18, 'name_fa' => 'کوراسائو', 'name_en' => 'Curacao', 'fifa_code' => 'CUW', 'flag' => 'cw'],
            ['id' => 19, 'name_fa' => 'ساحل عاج', 'name_en' => 'Ivory Coast', 'fifa_code' => 'CIV', 'flag' => 'ci'],
            ['id' => 20, 'name_fa' => 'اکوادور', 'name_en' => 'Ecuador', 'fifa_code' => 'ECU', 'flag' => 'ec'],
            ['id' => 21, 'name_fa' => 'هلند', 'name_en' => 'Netherlands', 'fifa_code' => 'NED', 'flag' => 'nl'],
            ['id' => 22, 'name_fa' => 'ژاپن', 'name_en' => 'Japan', 'fifa_code' => 'JPN', 'flag' => 'jp'],
            ['id' => 23, 'name_fa' => 'سوئد', 'name_en' => 'Sweden', 'fifa_code' => 'SWE', 'flag' => 'se'],
            ['id' => 24, 'name_fa' => 'تونس', 'name_en' => 'Tunisia', 'fifa_code' => 'TUN', 'flag' => 'tn'],
            ['id' => 25, 'name_fa' => 'بلژیک', 'name_en' => 'Belgium', 'fifa_code' => 'BEL', 'flag' => 'be'],
            ['id' => 26, 'name_fa' => 'مصر', 'name_en' => 'Egypt', 'fifa_code' => 'EGY', 'flag' => 'eg'],
            ['id' => 27, 'name_fa' => 'ایران', 'name_en' => 'Iran', 'fifa_code' => 'IRN', 'flag' => 'ir'],
            ['id' => 28, 'name_fa' => 'نیوزیلند', 'name_en' => 'New Zealand', 'fifa_code' => 'NZL', 'flag' => 'nz'],
            ['id' => 29, 'name_fa' => 'اسپانیا', 'name_en' => 'Spain', 'fifa_code' => 'ESP', 'flag' => 'es'],
            ['id' => 30, 'name_fa' => 'کیپ ورد', 'name_en' => 'Cape Verde', 'fifa_code' => 'CPV', 'flag' => 'cv'],
            ['id' => 31, 'name_fa' => 'عربستان سعودی', 'name_en' => 'Saudi Arabia', 'fifa_code' => 'KSA', 'flag' => 'sa'],
            ['id' => 32, 'name_fa' => 'اروگوئه', 'name_en' => 'Uruguay', 'fifa_code' => 'URU', 'flag' => 'uy'],
            ['id' => 33, 'name_fa' => 'فرانسه', 'name_en' => 'France', 'fifa_code' => 'FRA', 'flag' => 'fr'],
            ['id' => 34, 'name_fa' => 'سنگال', 'name_en' => 'Senegal', 'fifa_code' => 'SEN', 'flag' => 'sn'],
            ['id' => 35, 'name_fa' => 'عراق', 'name_en' => 'Iraq', 'fifa_code' => 'IRQ', 'flag' => 'iq'],
            ['id' => 36, 'name_fa' => 'نروژ', 'name_en' => 'Norway', 'fifa_code' => 'NOR', 'flag' => 'no'],
            ['id' => 37, 'name_fa' => 'آرژانتین', 'name_en' => 'Argentina', 'fifa_code' => 'ARG', 'flag' => 'ar'],
            ['id' => 38, 'name_fa' => 'الجزایر', 'name_en' => 'Algeria', 'fifa_code' => 'ALG', 'flag' => 'dz'],
            ['id' => 39, 'name_fa' => 'اتریش', 'name_en' => 'Austria', 'fifa_code' => 'AUT', 'flag' => 'at'],
            ['id' => 40, 'name_fa' => 'اردن', 'name_en' => 'Jordan', 'fifa_code' => 'JOR', 'flag' => 'jo'],
            ['id' => 41, 'name_fa' => 'پرتغال', 'name_en' => 'Portugal', 'fifa_code' => 'POR', 'flag' => 'pt'],
            ['id' => 42, 'name_fa' => 'کنگو DR', 'name_en' => 'DR Congo', 'fifa_code' => 'COD', 'flag' => 'cd'],
            ['id' => 43, 'name_fa' => 'ازبکستان', 'name_en' => 'Uzbekistan', 'fifa_code' => 'UZB', 'flag' => 'uz'],
            ['id' => 44, 'name_fa' => 'کلمبیا', 'name_en' => 'Colombia', 'fifa_code' => 'COL', 'flag' => 'co'],
            ['id' => 45, 'name_fa' => 'انگلیس', 'name_en' => 'England', 'fifa_code' => 'ENG', 'flag' => 'gb-eng'],
            ['id' => 46, 'name_fa' => 'کرواسی', 'name_en' => 'Croatia', 'fifa_code' => 'CRO', 'flag' => 'hr'],
            ['id' => 47, 'name_fa' => 'غنا', 'name_en' => 'Ghana', 'fifa_code' => 'GHA', 'flag' => 'gh'],
            ['id' => 48, 'name_fa' => 'پاناما', 'name_en' => 'Panama', 'fifa_code' => 'PAN', 'flag' => 'pa'],
        ];

        foreach ($teams as $team) {
            DB::table('teams')->updateOrInsert(['id' => $team['id']], [
                'name_fa' => $team['name_fa'],
                'name_en' => $team['name_en'],
                'fifa_code' => $team['fifa_code'],
                'flag_url' => "assets/worldcup/flags/{$team['flag']}.svg",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedScoringRules(): void
    {
        $rules = [
            ['key' => 'exact', 'title' => 'نتیجه دقیق', 'description' => 'پیش‌بینی کامل نتیجه بازی', 'points' => 100, 'sort_order' => 1],
            ['key' => 'winner_goal_diff', 'title' => 'برنده + تفاضل گل', 'description' => 'تیم برنده و اختلاف گل درست', 'points' => 50, 'sort_order' => 2],
            ['key' => 'winner_only', 'title' => 'فقط تیم برنده', 'description' => 'فقط تیم برنده درست حدس زده شده', 'points' => 30, 'sort_order' => 3],
            ['key' => 'wrong', 'title' => 'پیش‌بینی نادرست', 'description' => 'امتیاز شرکت در پیش‌بینی', 'points' => 10, 'sort_order' => 4],
        ];

        foreach ($rules as $rule) {
            DB::table('scoring_rules')->updateOrInsert(
                ['key' => $rule['key']],
                array_merge($this->hasCampaignColumn('scoring_rules') ? ['campaign_id' => $this->campaignId] : [], [
                    'title' => $rule['title'],
                    'description' => $rule['description'],
                    'points' => $rule['points'],
                    'is_active' => true,
                    'sort_order' => $rule['sort_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    private function seedPrizes(): void
    {
        $prizes = [
            ['rank_from' => 1, 'rank_to' => 1, 'title' => 'نفر اول', 'prize_amount' => 5],
            ['rank_from' => 2, 'rank_to' => 2, 'title' => 'نفر دوم', 'prize_amount' => 3],
            ['rank_from' => 3, 'rank_to' => 3, 'title' => 'نفر سوم', 'prize_amount' => 1],
        ];

        foreach ($prizes as $index => $prize) {
            DB::table('prizes')->updateOrInsert([
                'campaign_id' => $this->campaignId,
                'rank_from' => $prize['rank_from'],
                'rank_to' => $prize['rank_to'],
            ], [
                'title' => $prize['title'],
                'prize_amount' => $prize['prize_amount'],
                'prize_unit' => 'گرم طلا',
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['question' => 'مسابقه پیش‌بینی جام جهانی رشت‌گلد چیست؟', 'answer' => 'یک رویداد هیجان‌انگیز که با پیش‌بینی نتایج بازی‌های جام جهانی، امکان برنده شدن جایزه طلا را فراهم می‌کند.'],
            ['question' => 'آیا می‌توانم پیش‌بینی خود را ویرایش کنم؟', 'answer' => 'بله، تا قبل از شروع بازی می‌توانید پیش‌بینی خود را ویرایش کنید. بعد از شروع بازی، پیش‌بینی قفل می‌شود.'],
            ['question' => 'امتیازها چطور محاسبه می‌شوند؟', 'answer' => 'امتیازها براساس دقت پیش‌بینی نتیجه، برنده و تفاضل گل محاسبه می‌شوند.'],
        ];

        foreach ($faqs as $index => $faq) {
            DB::table('faqs')->updateOrInsert(
                ['question' => $faq['question']],
                array_merge($this->hasCampaignColumn('faqs') ? ['campaign_id' => $this->campaignId] : [], [
                    'answer' => $faq['answer'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    private function seedMatches(): void
    {
        /*
         * فقط بازی‌های امشب به بعد که تیم‌هایشان مشخص است.
         * زمان‌ها بر اساس Eastern Time هستند و به UTC تبدیل می‌شوند.
         * چون home_team_id و away_team_id در دیتابیس nullable نیستند،
         * بازی‌های ربع‌نهایی، نیمه‌نهایی و فینال تا وقتی تیم‌ها مشخص نشده‌اند seed نمی‌شوند.
         */

        $matches = [
            // Round of 16 - امشب به بعد
            [
                'external_id' => 'wc2026-r16-01',
                'stage_id' => 3,
                'home' => 5,   // Canada
                'away' => 10,  // Morocco
                'date' => '2026-07-04 13:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-02',
                'stage_id' => 3,
                'home' => 14,  // Paraguay
                'away' => 33,  // France
                'date' => '2026-07-04 17:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-03',
                'stage_id' => 3,
                'home' => 9,   // Brazil
                'away' => 36,  // Norway
                'date' => '2026-07-05 16:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-04',
                'stage_id' => 3,
                'home' => 1,   // Mexico
                'away' => 45,  // England
                'date' => '2026-07-05 20:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-05',
                'stage_id' => 3,
                'home' => 41,  // Portugal
                'away' => 29,  // Spain
                'date' => '2026-07-06 15:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-06',
                'stage_id' => 3,
                'home' => 13,  // United States
                'away' => 25,  // Belgium
                'date' => '2026-07-06 20:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-07',
                'stage_id' => 3,
                'home' => 37,  // Argentina
                'away' => 26,  // Egypt
                'date' => '2026-07-07 12:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
            [
                'external_id' => 'wc2026-r16-08',
                'stage_id' => 3,
                'home' => 8,   // Switzerland
                'away' => 44,  // Colombia
                'date' => '2026-07-07 16:00:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'scheduled',
            ],
        ];

        foreach ($matches as $match) {
            $matchDateUtc = Carbon::parse($match['date'], 'America/New_York')->utc();

            DB::table('matches')->updateOrInsert(
                ['external_id' => $match['external_id']],
                [
                    'campaign_id' => $this->campaignId,
                    'stage_id' => $match['stage_id'],
                    'home_team_id' => $match['home'],
                    'away_team_id' => $match['away'],
                    'match_date' => $matchDateUtc->toDateTimeString(),
                    'prediction_deadline' => $matchDateUtc->toDateTimeString(),
                    'home_score' => $match['home_score'],
                    'away_score' => $match['away_score'],
                    'status' => $match['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
    private function hasCampaignColumn(string $table): bool
    {
        return Schema::hasColumn($table, 'campaign_id');
    }
}
