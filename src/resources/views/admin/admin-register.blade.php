@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-register.css') }}">
@endsection

@section('nav')

<input class="hamburger-input" type="checkbox" id="check">
<label class="hamburger-label" for="check">
    <span></span>
</label>
<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item"><a class="header-nav__link" href="/">Home</a></li>
        <li class="header-nav__item">
            <form class="header-nav__form" action="/logout" method="post">
                @csrf
                <button class="header-nav__link--button" type="submit">Logout</button>
            </form>
        </li>
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('admin.dashboard') }}">管理画面</a></li>
    </ul>
</nav>


@endsection

@section('content')

@if (session('message'))
<div class="admin-register-form__session-message">

    {!! session('message') !!}

</div>
@endif

<div class="admin-register-form">
    <h2 class="admin-register-form__heading">Admin Registration</h2>
    <form class="admin-register-form__form" action="{{ route('admin.adminUser') }}" method="post" novalidate>
        @csrf
        <div class="admin-register-form__group">
            <div class="admin-register-form__input-container">
                <i class="fa-solid fa-user fa-xl"></i>
                <input class="admin-register-form__input" type="text" name="name" id="name" placeholder="Username" value="{{ old('name') }}">
            </div>
            <p class="admin-register-form__error-message">
                @error('name')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="admin-register-form__group">
            <div class="admin-register-form__input-container">
                <i class="fa-solid fa-envelope fa-xl"></i>
                <input class="admin-register-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
            </div>
            <p class="admin-register-form__error-message">
                @error('email')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="admin-register-form__group">
            <div class="admin-register-form__input-container">
                <i class="fa-solid fa-lock fa-xl"></i>
                <input class="admin-register-form__input" type="password" name="password" id="password" placeholder="Password">
            </div>
            <p class="admin-register-form__error-message">
                @error('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="admin-register-form__group">
            <div class="admin-register-form__input-container">
                <i class="fa-solid fa-tag fa-xl"></i>
                <select class="admin-register-form__select" name="role" id="role" required>
                    <option value="store_manager" selected>店舗代表者</option>
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <p class="admin-register-form__error-message">
                @error('role')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="admin-register-form__button-container">
            <button class="admin-register-form__button" type="submit">登録</button>
        </div>
    </form>
</div>


@endsection