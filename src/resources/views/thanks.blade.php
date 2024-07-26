@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
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
<div class="thanks-form">
    <h2 class="thanks-form__heading">会員登録ありがとうございます</h2>
    <div class="thanks-form__button-container">
        <a class="thanks-form__button" href="/login">ログインする</a>
    </div>
</div>


@endsection