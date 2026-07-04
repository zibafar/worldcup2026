@extends('layouts.worldcup-admin')
@section('title', 'افزودن بازی')
@section('content')
<div class="sec-head"><div class="sec-dot"></div><h2>افزودن بازی جدید</h2></div>
<div class="admin-card">
<form class="admin-form" method="POST" action="{{ route('admin.worldcup.matches.store') }}">
@csrf
@include('admin.worldcup.matches._form', ['match' => null])
<button class="admin-btn" type="submit">ثبت بازی</button>
</form>
</div>
@endsection
