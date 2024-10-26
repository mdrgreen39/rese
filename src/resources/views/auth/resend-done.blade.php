@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/resend-done.css') }}">
@endsection

@section('nav')
<input class="hamburger-input" type="checkbox" id="check">
<label class="hamburger-label" for="check">
    <span></span>
</label>
<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item"><a class="header-nav__link" href="/">Home</a></li>
        <li class="header-nav__item"><a class="header-nav__link" href="/register">Registration</a></li>
        <li class="header-nav__item"><a class="header-nav__link" href="/login">Login</a></li>
    </ul>
</nav>

@endsection

@section('content')
<div class="resend-done">
    <h2 class="resend-done__heading">メールを再送信しました</h2>
    <div class="resend-done__button-container">
        <a class="resend-done__button" href="/login">ログインする</a>
    </div>
</div>

@endsection