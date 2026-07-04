@extends('layouts.worldcup-admin')
@section('title', 'مدیریت سوالات متداول')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>سوالات متداول</h2></div>
<div class="admin-card">
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.faqs.store') }}">
@csrf
<label>سوال<input name="question" required></label>
<label>ترتیب<input type="number" name="sort_order" min="0" value="0"></label>
<label>فعال<select name="is_active"><option value="1">بله</option><option value="0">خیر</option></select></label>
<label>پاسخ<textarea name="answer" required></textarea></label>
<button class="admin-btn" type="submit">ثبت سوال</button>
</form>
</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>سوال</th><th>پاسخ</th><th>ترتیب</th><th>فعال</th></tr></thead><tbody>
@forelse($faqs as $faq)<tr><td>{{ $faq->question }}</td><td>{{ Str::limit($faq->answer, 90) }}</td><td>{{ $fa($faq->sort_order) }}</td><td>{{ $faq->is_active ? 'بله' : 'خیر' }}</td></tr>@empty<tr><td colspan="4">سوالی ثبت نشده است.</td></tr>@endforelse
</tbody></table></div>
@endsection
