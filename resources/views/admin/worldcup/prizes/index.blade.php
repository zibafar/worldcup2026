@extends('layouts.worldcup-admin')
@section('title', 'مدیریت جوایز')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>جوایز</h2></div>
<div class="admin-card">
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.prizes.store') }}">
@csrf
<label>از رتبه<input type="number" name="rank_from" min="1" required></label>
<label>تا رتبه<input type="number" name="rank_to" min="1" required></label>
<label>عنوان<input name="title" placeholder="نفر اول" required></label>
<label>مقدار جایزه<input type="number" step="0.01" name="prize_amount" min="0" required></label>
<label>واحد<input name="prize_unit" value="گرم طلا" required></label>
<label>ترتیب<input type="number" name="sort_order" min="0" value="0"></label>
<label>فعال<select name="is_active"><option value="1">بله</option><option value="0">خیر</option></select></label>
<button class="admin-btn" type="submit">ثبت جایزه</button>
</form>
</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>رتبه</th><th>عنوان</th><th>جایزه</th><th>فعال</th></tr></thead><tbody>
@forelse($prizes as $prize)<tr><td>{{ $fa($prize->rank_from) }} تا {{ $fa($prize->rank_to) }}</td><td>{{ $prize->title }}</td><td>{{ $fa($prize->prize_amount) }} {{ $prize->prize_unit }}</td><td>{{ $prize->is_active ? 'بله' : 'خیر' }}</td></tr>@empty<tr><td colspan="4">جایزه‌ای ثبت نشده است.</td></tr>@endforelse
</tbody></table></div>
@endsection
