@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
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

@if (session('message'))
<div class="login-form__session-message">
    {{ session('message') }}
</div>
@endif

<div class="login-form">
    <h2 class="login-form__heading">Login</h2>
    <form class="login-form__form" action="/login" method="post" novalidate>
        @csrf
        <div class="login-form__group">
            <div class="login-form__input-container">
                <i class="fa-solid fa-envelope fa-xl"></i>
                <input class="login-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
            </div>
            <p class="login-form__error-message">
                @error('email')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="login-form__group">
            <div class="login-form__input-container">
                <i class="fa-solid fa-lock fa-xl"></i>
                <input class="login-form__input" type="password" name="password" id="password" placeholder="Password">
            </div>
            <p class="login-form__error-message">
                @error('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="login-form__button-container">
            <button class="login-form__button" type="submit">ログイン</button>
        </div>
    </form>
</div>

@endsection