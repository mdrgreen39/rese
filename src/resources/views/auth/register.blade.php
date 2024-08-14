@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
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
<div class="register-form__session-message">

    {!! session('message') !!}

</div>
@endif

<div class="register-form">
    <h2 class="register-form__heading">Registration</h2>
    <form class="register-form__form" action="/register" method="post" novalidate>
        @csrf
        <div class="register-form__group">
            <div class="register-form__input-container">
                <img class="register-form__user-icon" src="/images/icons/icon_user.svg" alt="user_icon">
                <input class="register-form__input" type="text" name="name" id="name" placeholder="Username" value="{{ old('name') }}">
            </div>
            <p class="register-form__error-message">
                @error('name')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="register-form__group">
            <div class="register-form__input-container">
                <img class="register-form__email-icon" src="/images/icons/icon_email.svg" alt="email_icon">
                <input class="register-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
            </div>
            <p class="register-form__error-message">
                @error('email')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="register-form__group">
            <div class="register-form__input-container">
                <img class="register-form__password-icon" src="/images/icons/icon_password.svg" alt="password_icon">
                <input class="register-form__input" type="password" name="password" id="password" placeholder="Password">
            </div>
            <p class="register-form__error-message">
                @error('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="register-form__button-container">
            <button class="register-form__button" type="submit">登録</button>
        </div>
    </form>
</div>


@endsection