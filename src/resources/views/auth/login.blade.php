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
<div class="login-form">
    <h2 class="login-form__heading">Login</h2>
    <div class="login-form__inner">
        <form class="login-form__form" action="/login" method="post" novalidate>
            @csrf
            <div class="login-form__group">
                <div class="login-form__input-container">
                    <img class="login-form__email-icon" src="/images/icons/icon_email.svg" alt="email_icon">
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
                    <img class="login-form__password-icon" src="/images/icons/icon_password.svg" alt="password_icon">
                    <input class="login-form__input" type="password" name="password" id="password" placeholder="Password" value="{{ old('password') }}">

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
</div>


@endsection