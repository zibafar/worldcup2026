@extends('layouts.worldcup-admin')
@section('title', 'رده‌بندی کاربران')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>رده‌بندی کاربران</h2></div>
<div class="admin-card">
<table class="admin-table"><thead><tr><th>رتبه</th><th>نام</th><th>موبایل</th><th>امتیاز</th><th>پیش‌بینی دقیق</th><th>کل پیش‌بینی</th></tr></thead><tbody>
@forelse($leaderboard as $row)<tr><td>{{ $fa(($leaderboard->currentPage()-1)*$leaderboard->perPage()+$loop->iteration) }}</td><td>{{ $row->name }}</td><td>{{ $row->mobile }}</td><td>{{ $fa($row->total_points) }}</td><td>{{ $fa($row->exact_count) }}</td><td>{{ $fa($row->predictions_count) }}</td></tr>@empty<tr><td colspan="6">اطلاعاتی وجود ندارد.</td></tr>@endforelse
</tbody></table>
<div style="margin-top:14px">{{ $leaderboard->links() }}</div>
</div>
@endsection
