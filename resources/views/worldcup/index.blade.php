@extends('layouts.worldcup')

@section('title', ($campaign?->title ?? 'پیش‌بینی جام جهانی') . ' | رشت‌گلد')

@section('content')
@php
    $fa = fn($value) => strtr((string) $value, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
    $score = fn($value) => $fa(number_format((float) $value));
    $user = auth()->user();
    $exactCount = (int) ($myStats->exact_count ?? $user?->exact_predictions_count ?? 0);
    $totalScore = (int) ($myStats->total_points ?? $user?->total_score ?? 0);
@endphp

<nav>
    <a class="nav-logo" href="https://rashtgold.com" target="_blank">
        <div class="logo-coin">RG</div>
        <div class="logo-txt">
            <span class="l1">رشت‌گلد</span>
            <span class="l2">rashtgold.com</span>
        </div>
    </a>

    @guest
        <div class="nav-right" id="navGuest">
            <a class="btn-nav btn-ghost" href="{{ url('/login') }}">ورود</a>
            <a class="btn-nav btn-gold" href="{{ url('/register') }}">ثبت‌نام</a>
        </div>
    @else
        <div class="nav-right" id="navUser">
            <div class="user-chip">
                <div class="user-avatar">{{ mb_substr($user->name ?? 'ک', 0, 1) }}</div>
                <span class="user-name">{{ $user->name }}</span>
            </div>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button class="btn-logout" type="submit">خروج</button>
            </form>
        </div>
    @endguest
</nav>

<div class="hero">
    <div class="bg-obj ball1">⚽</div>
    <div class="bg-obj ball2">⚽</div>
    <div class="bg-obj ball3">⚽</div>
    <div class="bg-obj coin1">🪙</div>
    <div class="bg-obj coin2">🪙</div>
    <div class="bg-obj coin3">💰</div>

    <div class="hero-eyebrow">⚽ رشت‌گلد · جام جهانی ۲۰۲۶</div>
    <div class="hero-title">
        {{ $campaign?->title ?? 'پیش‌بینی جام جهانی' }} <span class="gold-w">رشت‌گلد</span>
    </div>
    <div class="hero-sub">{{ $campaign?->subtitle ?? 'پیش‌بینی کن و ۱۰ گرم طلا ببر' }}</div>

    @guest
        <div id="heroCTA">
            <a class="hero-cta" href="{{ url('/login') }}">🏆 همین حالا شروع کن و جایزه ببر</a>
        </div>
    @else
        <div class="hero-user-card show" id="heroUserCard">
            <div class="huc-row">
                <div class="huc-avatar">{{ mb_substr($user->name ?? 'ک', 0, 1) }}</div>
                <div class="huc-info">
                    <div class="huc-name">{{ $user->name }}</div>
                    <div class="huc-stats">
                        <div class="huc-stat"><span class="val gold">{{ $score($totalScore) }}</span><span class="lbl">امتیاز</span></div>
                        <div class="huc-stat"><span class="val green">#{{ $fa($myRank ?? '-') }}</span><span class="lbl">رتبه</span></div>
                        <div class="huc-stat"><span class="val">{{ $fa($exactCount) }}</span><span class="lbl">پیش‌بینی دقیق</span></div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>

<div class="container">
    @if(session('success'))
        <div class="live-counter" style="margin-top:20px;color:var(--green)">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="live-counter" style="margin-top:20px;color:#ff6b6b">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="live-counter" style="margin-top:20px;color:#ff6b6b">
            @foreach($errors->all() as $error)<span>{{ $error }}</span>@endforeach
        </div>
    @endif

    <div class="rules-prizes-wrap au">
        <div class="rules-card">
            <div class="rules-head">
                <span class="rules-icon">📋</span>
                <div><div class="rules-title">قوانین امتیازدهی</div><div class="rules-sub">هر پیش‌بینی چقدر ارزش دارد؟</div></div>
            </div>
            <div class="rules-grid">
                @forelse($scoringRules as $rule)
                    @php
                        $class = match($rule->key) {
                            'exact' => 'ri-gold',
                            'winner_goal_diff' => 'ri-green',
                            'winner_only' => 'ri-blue',
                            default => 'ri-muted',
                        };
                        $icon = match($rule->key) {
                            'exact' => '🎯',
                            'winner_goal_diff' => '⚖️',
                            'winner_only' => '✅',
                            default => '📝',
                        };
                    @endphp
                    <div class="rule-item {{ $class }}">
                        <div class="rule-pts">{{ $fa($rule->points) }}</div>
                        <div class="rule-icon-big">{{ $icon }}</div>
                        <div class="rule-desc"><div class="rule-title">{{ $rule->title }}</div><div class="rule-note">{{ $rule->description }}</div></div>
                    </div>
                @empty
                    <div class="rule-item ri-gold"><div class="rule-pts">۱۰۰</div><div class="rule-icon-big">🎯</div><div class="rule-desc"><div class="rule-title">نتیجه دقیق</div><div class="rule-note">پیش‌بینی کامل نتیجه بازی</div></div></div>
                    <div class="rule-item ri-green"><div class="rule-pts">۵۰</div><div class="rule-icon-big">⚖️</div><div class="rule-desc"><div class="rule-title">برنده + تفاضل گل</div><div class="rule-note">تیم برنده و اختلاف گل درست</div></div></div>
                    <div class="rule-item ri-blue"><div class="rule-pts">۳۰</div><div class="rule-icon-big">✅</div><div class="rule-desc"><div class="rule-title">فقط تیم برنده</div><div class="rule-note">فقط تیم برنده درست حدس زده شود</div></div></div>
                    <div class="rule-item ri-muted"><div class="rule-pts">۱۰</div><div class="rule-icon-big">📝</div><div class="rule-desc"><div class="rule-title">پیش‌بینی نادرست</div><div class="rule-note">امتیاز شرکت در پیش‌بینی</div></div></div>
                @endforelse
            </div>
        </div>

        <div class="prizes-card">
            <div class="prizes-head">
                <span class="rules-icon">🏆</span>
                <div><div class="rules-title">جوایز نفرات برتر</div><div class="rules-sub">{{ $campaign?->total_prize_title ?? '۱۰ گرم طلا' }}</div></div>
            </div>
            <div class="prizes-list">
                @forelse($prizes as $prize)
                    @php $rankClass = $loop->iteration === 1 ? 'pr-1' : ($loop->iteration === 2 ? 'pr-2' : ($loop->iteration === 3 ? 'pr-3' : 'pr-rest')); @endphp
                    <div class="prize-row {{ $rankClass }}">
                        <div class="pr-rank">{{ ['🥇','🥈','🥉'][$loop->index] ?? '🏅' }}</div>
                        <div class="pr-label">{{ $prize->title }}</div>
                        <div class="pr-amount">{{ $fa($prize->prize_amount) }} {{ $prize->prize_unit }}</div>
                    </div>
                @empty
                    <div class="prize-row pr-1"><div class="pr-rank">🥇</div><div class="pr-label">نفر اول</div><div class="pr-amount">۵ گرم طلا</div></div>
                    <div class="prize-row pr-2"><div class="pr-rank">🥈</div><div class="pr-label">نفر دوم</div><div class="pr-amount">۳ گرم طلا</div></div>
                    <div class="prize-row pr-3"><div class="pr-rank">🥉</div><div class="pr-label">نفر سوم</div><div class="pr-amount">۱ گرم طلا</div></div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="sec-head"><div class="sec-dot"></div><h2>پیش‌بینی‌های من</h2></div>
    <div class="tabs" id="predictionTabs">
        <button class="tab active" onclick="showPredictionTab('upcoming',this)">⚽ پیش‌رو ({{ $fa($upcomingMatches->count()) }})</button>
        <button class="tab" onclick="showPredictionTab('past',this)">📜 گذشته ({{ $fa($pastMatches->count()) }})</button>
    </div>

    <div id="upcomingPanel">
        <div class="sec-head au" id="upcoming-matches"><div class="sec-dot"></div><h2>بازی‌های پیش رو</h2></div>
        <div class="matches-grid">
            @forelse($upcomingMatches as $match)
                @php
                    $prediction = $myPredictions->get($match->id);
                    $locked = $match->prediction_deadline && now()->greaterThanOrEqualTo($match->prediction_deadline);
                @endphp
                <form method="POST" action="{{ route('worldcup.predictions.submit', $match) }}" class="match-card state-pending {{ $prediction ? 'predicted' : '' }} au" id="match-card-{{ $match->id }}">
                    @csrf
                    <div class="card-badge-top"><span class="badge-worldcup"><span class="dot"></span>جام جهانی</span><span class="stage-txt">{{ $match->stage?->title }}</span></div>
                    <div class="match-teams">
                        <div class="team"><div class="team-flag"><img src="{{ $match->homeTeam?->flag_url }}" alt="{{ $match->homeTeam?->name_en }}"></div><div class="team-name">{{ $match->homeTeam?->name_fa }}</div></div>
                        <div class="score-center">
                            <div class="score-row">
                                <input class="score-inp" name="predicted_home_score" type="number" min="0" max="99" placeholder="0" value="{{ $prediction?->predicted_home_score }}" @disabled($locked)>
                                <span class="score-sep">:</span>
                                <input class="score-inp" name="predicted_away_score" type="number" min="0" max="99" placeholder="0" value="{{ $prediction?->predicted_away_score }}" @disabled($locked)>
                            </div>
                            <div class="countdown-wrap" data-countdown="{{ optional($match->prediction_deadline ?? $match->match_date)->toIso8601String() }}">
                                <div class="countdown-time">۰۰ : ۰۰ : ۰۰</div><div class="countdown-lbl">تا شروع بازی</div>
                            </div>
                        </div>
                        <div class="team"><div class="team-flag"><img src="{{ $match->awayTeam?->flag_url }}" alt="{{ $match->awayTeam?->name_en }}"></div><div class="team-name">{{ $match->awayTeam?->name_fa }}</div></div>
                    </div>
                    @if($prediction)<div class="predicted-badge"><span></span> پیش‌بینی ثبت شد</div>@endif
                    @auth
                        <button class="btn-card {{ $locked ? 'btn-locked' : ($prediction ? 'btn-edit' : 'btn-submit') }}" type="submit" @disabled($locked)>{{ $locked ? '🔒 بازی شروع شده' : ($prediction ? '✏️ ویرایش پیش‌بینی' : 'ثبت پیش‌بینی') }}</button>
                    @else
                        <a class="btn-card btn-submit" href="{{ url('/login') }}" style="text-align:center;text-decoration:none">برای پیش‌بینی وارد شوید</a>
                    @endauth
                </form>
            @empty
                <div class="match-card"><div class="team-name">فعلاً بازی پیش‌رویی ثبت نشده است.</div></div>
            @endforelse
        </div>
    </div>

    <div id="pastPanel">
        <div class="sec-head au" id="past-matches"><div class="sec-dot"></div><h2>بازی‌های گذشته</h2></div>
        <div class="matches-grid">
            @forelse($pastMatches as $match)
                @php $prediction = $match->predictions->first(); @endphp
                <div class="match-card state-finished {{ $prediction ? 'predicted' : '' }} au" id="past-match-card-{{ $match->id }}">
                    <div class="card-badge-top"><span class="badge-points"><span class="star">⭐</span>{{ $fa($prediction?->points ?? 0) }} امتیاز</span><span class="stage-txt">{{ $match->stage?->title }}</span></div>
                    <div class="match-teams">
                        <div class="team"><div class="team-flag"><img src="{{ $match->homeTeam?->flag_url }}" alt="{{ $match->homeTeam?->name_en }}"></div><div class="team-name">{{ $match->homeTeam?->name_fa }}</div></div>
                        <div class="score-center"><div class="predicted-score-center">{{ $prediction ? $fa($prediction->predicted_home_score).' - '.$fa($prediction->predicted_away_score) : '—' }}</div><div class="predicted-lbl-center">پیش‌بینی شما</div></div>
                        <div class="team"><div class="team-flag"><img src="{{ $match->awayTeam?->flag_url }}" alt="{{ $match->awayTeam?->name_en }}"></div><div class="team-name">{{ $match->awayTeam?->name_fa }}</div></div>
                    </div>
                    <div class="result-final-box"><div class="result-final-score">{{ $fa($match->home_score) }} - {{ $fa($match->away_score) }}</div><div class="result-final-lbl">نتیجه بازی</div></div>
                </div>
            @empty
                <div class="match-card"><div class="team-name">هنوز بازی تمام‌شده‌ای ثبت نشده است.</div></div>
            @endforelse
        </div>
    </div>

    <div id="leaderboard">
        <div class="lb-hero">
            <h3>جوایز نفرات برتر</h3>
            <p>جوایز نفرات اول تا سوم به شرح زیر است</p>
            <div class="podium">
                <div class="podium-item"><div class="pod-prize silver">۳ گرم طلا</div><div class="pod-av silver">⚽</div><div class="pod-plat pp2">۲</div></div>
                <div class="podium-item"><div class="pod-prize gold">۵ گرم طلا</div><div class="pod-av gold">⚽</div><div class="pod-plat pp1">۱</div></div>
                <div class="podium-item"><div class="pod-prize bronze">۱ گرم طلا</div><div class="pod-av bronze">⚽</div><div class="pod-plat pp3">۳</div></div>
            </div>
        </div>
        <div class="sec-head"><div class="sec-dot"></div><h2>رده‌بندی و امتیازات</h2></div>
        <div class="rankings">
            @forelse($leaderboard as $row)
                <div class="rank-row au">
                    <div class="rbadge {{ $loop->iteration === 1 ? 'rb1' : ($loop->iteration === 2 ? 'rb2' : ($loop->iteration === 3 ? 'rb3' : '') ) }}">{{ $fa($loop->iteration) }}</div>
                    <div class="rank-info"><div class="rank-phone">{{ $row->mobile }}</div><div class="rank-meta">{{ $fa($row->exact_count ?? 0) }} پیش‌بینی دقیق</div></div>
                    <div class="rank-score"><div class="sc">{{ $score($row->total_points ?? 0) }}</div><div class="sl">امتیاز</div></div>
                </div>
            @empty
                <div class="rank-row"><div class="rank-info"><div class="rank-phone">هنوز رتبه‌بندی خالی است.</div></div></div>
            @endforelse
        </div>
        <div class="live-counter"><div class="live-dot"></div><span><span class="live-num" id="liveNum" data-base="{{ max(1, $leaderboard->count()) }}">{{ $fa(number_format(max(1, $leaderboard->count()))) }}</span> نفر در حال رقابت</span></div>
    </div>

    <div class="faq-section">
        <h2>سوالات متداول</h2>
        <div class="faq-list">
            @forelse($faqs as $faq)
                <div class="faq-item {{ $loop->first ? 'open' : '' }}"><div class="faq-q">{{ $faq->question }}</div><div class="faq-a">{{ $faq->answer }}</div></div>
            @empty
                <div class="faq-item open"><div class="faq-q">مسابقه پیش‌بینی جام جهانی رشت‌گلد چیست؟</div><div class="faq-a">یک رویداد هیجان‌انگیز که با پیش‌بینی نتایج بازی‌های جام جهانی، تا ۱۰ گرم طلا برنده می‌شوید.</div></div>
                <div class="faq-item"><div class="faq-q">آیا می‌توانم پیش‌بینی خود را ویرایش کنم؟</div><div class="faq-a">بله، تا زمان شروع بازی می‌توانید پیش‌بینی خود را ویرایش کنید. بعد از شروع بازی، پیش‌بینی قفل می‌شود.</div></div>
            @endforelse
        </div>
    </div>
</div>

<footer>&copy; ۱۴۰۵ — <a href="https://rashtgold.com" target="_blank">رشت‌گلد</a> · پیش‌بینی جام جهانی. تمامی حقوق محفوظ است.</footer>
@endsection
