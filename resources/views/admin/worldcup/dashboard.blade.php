@extends('layouts.worldcup-admin')
@section('title', 'داشبورد مدیریت جام جهانی')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>داشبورد مدیریت مسابقه</h2></div>
<div class="rules-prizes-wrap">
    <div class="admin-card"><h3>کمپین فعال</h3><p>{{ $activeCampaign?->title ?? 'کمپین فعالی ثبت نشده است.' }}</p></div>
    <div class="admin-card"><h3>آمار کلی</h3><p>کاربران: {{ $fa($stats['users_count']) }} | بازی‌ها: {{ $fa($stats['matches_count']) }} | پیش‌بینی‌ها: {{ $fa($stats['predictions_count']) }} | بازی‌های تمام‌شده: {{ $fa($stats['finished_matches_count']) }}</p></div>
</div>
<div class="admin-card">
    <h3>۱۰ نفر برتر</h3>
    <table class="admin-table"><thead><tr><th>رتبه</th><th>کاربر</th><th>موبایل</th><th>امتیاز</th><th>دقیق</th></tr></thead><tbody>
    @forelse($leaderboard as $row)<tr><td>{{ $fa($loop->iteration) }}</td><td>{{ $row->name }}</td><td>{{ $row->mobile }}</td><td>{{ $fa($row->total_points) }}</td><td>{{ $fa($row->exact_count) }}</td></tr>@empty<tr><td colspan="5">اطلاعاتی وجود ندارد.</td></tr>@endforelse
    </tbody></table>
</div>
<div class="admin-card">
    <h3>آخرین پیش‌بینی‌ها</h3>
    <table class="admin-table"><thead><tr><th>کاربر</th><th>بازی</th><th>پیش‌بینی</th><th>امتیاز</th></tr></thead><tbody>
    @forelse($latestPredictions as $prediction)<tr><td>{{ $prediction->user?->name }}</td><td>{{ $prediction->match?->homeTeam?->name_fa }} - {{ $prediction->match?->awayTeam?->name_fa }}</td><td>{{ $fa($prediction->predicted_home_score) }} - {{ $fa($prediction->predicted_away_score) }}</td><td>{{ $fa($prediction->points) }}</td></tr>@empty<tr><td colspan="4">هنوز پیش‌بینی ثبت نشده است.</td></tr>@endforelse
    </tbody></table>
</div>
@endsection
