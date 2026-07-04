@extends('layouts.worldcup-admin')
@section('title', 'ویرایش بازی')
@section('content')
<div class="sec-head"><div class="sec-dot"></div><h2>ویرایش بازی</h2></div>
<div class="admin-card">
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.matches.update', $match) }}">
@csrf @method('PUT')
@include('admin.worldcup.matches._form', ['match' => $match])
<button class="admin-btn" type="submit">ذخیره تغییرات</button>
</form>
</div>
<div class="admin-card">
<h3>ثبت نتیجه نهایی</h3>
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.matches.result', $match) }}">
@csrf
<label>گل تیم اول<input type="number" name="home_score" min="0" max="99" value="{{ old('home_score', $match->home_score) }}"></label>
<label>گل تیم دوم<input type="number" name="away_score" min="0" max="99" value="{{ old('away_score', $match->away_score) }}"></label>
<button class="admin-btn gold" type="submit">ثبت نتیجه و محاسبه امتیاز</button>
</form>
<form style="margin-top:12px" method="POST" action="{{ route('admin.worldcup.matches.recalculate', $match) }}">@csrf<button class="admin-btn ghost" type="submit">محاسبه دوباره امتیاز</button></form>
</div>
@endsection
