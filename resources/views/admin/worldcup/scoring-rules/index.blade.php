@extends('layouts.worldcup-admin')
@section('title', 'قوانین امتیازدهی')
@section('content')
@php $fa = fn($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']); @endphp
<div class="sec-head"><div class="sec-dot"></div><h2>قوانین امتیازدهی</h2></div>
<div class="admin-card">
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.scoring-rules.store') }}">
@csrf
<label>کلید<input name="key" placeholder="exact" required></label>
<label>عنوان<input name="title" placeholder="نتیجه دقیق" required></label>
<label>امتیاز<input type="number" name="points" min="0" required></label>
<label>ترتیب<input type="number" name="sort_order" min="0" value="0"></label>
<label>فعال<select name="is_active"><option value="1">بله</option><option value="0">خیر</option></select></label>
<label>توضیح<textarea name="description" placeholder="توضیح کوتاه"></textarea></label>
<button class="admin-btn" type="submit">ثبت قانون</button>
</form>
</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>کلید</th><th>عنوان</th><th>امتیاز</th><th>فعال</th></tr></thead><tbody>
@forelse($rules as $rule)<tr><td>{{ $rule->key }}</td><td>{{ $rule->title }}</td><td>{{ $fa($rule->points) }}</td><td>{{ $rule->is_active ? 'بله' : 'خیر' }}</td></tr>@empty<tr><td colspan="4">قانونی ثبت نشده است.</td></tr>@endforelse
</tbody></table></div>
@endsection
