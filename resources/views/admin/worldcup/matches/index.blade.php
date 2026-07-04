@extends('layouts.worldcup-admin')
@section('title', 'مدیریت بازی‌ها')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>بازی‌ها</h2></div>
<div class="admin-card">
    <form class="admin-form" method="GET" action="{{ route('admin.worldcup.matches') }}">
        <label>کمپین<select name="campaign_id"><option value="">همه</option>@foreach($campaigns as $campaign)<option value="{{ $campaign->id }}" @selected((string)$campaignId === (string)$campaign->id)>{{ $campaign->title }}</option>@endforeach</select></label>
        <label>وضعیت<select name="status"><option value="">همه</option>@foreach(['scheduled'=>'برنامه‌ریزی‌شده','live'=>'زنده','finished'=>'تمام‌شده','cancelled'=>'لغوشده'] as $key=>$label)<option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>@endforeach</select></label>
        <button class="admin-btn" type="submit">فیلتر</button>
        <a class="admin-btn gold" href="{{ route('admin.worldcup.matches.create') }}">افزودن بازی</a>
    </form>
</div>
<div class="admin-card">
    <table class="admin-table"><thead><tr><th>بازی</th><th>مرحله</th><th>زمان</th><th>وضعیت</th><th>نتیجه</th><th>عملیات</th></tr></thead><tbody>
    @forelse($matches as $match)
        <tr>
            <td>{{ $match->homeTeam?->name_fa }} - {{ $match->awayTeam?->name_fa }}</td><td>{{ $match->stage?->title }}</td><td>{{ $match->match_date?->format('Y-m-d H:i') }}</td><td><span class="status">{{ $match->status }}</span></td><td>{{ $match->home_score !== null ? $fa($match->home_score).' - '.$fa($match->away_score) : '—' }}</td><td><a class="admin-btn ghost" href="{{ route('admin.worldcup.matches.edit', $match) }}">ویرایش</a></td>
        </tr>
    @empty<tr><td colspan="6">بازی ثبت نشده است.</td></tr>@endforelse
    </tbody></table>
    <div style="margin-top:14px">{{ $matches->links() }}</div>
</div>
@endsection
