@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/email-notification.css') }}">
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
<div class="email-form__session-message">

    {!! session('message') !!}

</div>
@endif

<div class="email-form">
    <h2 class="email-form__heading">メール送信フォーム</h2>
    <form class="email-form__form" action="{{ route('admin.sendNotification') }}" method="post" novalidate>
        @csrf
        <div class="email-form__group">
            <div class="email-form__input-container">
                <i class="fa-solid fa-user fa-xl"></i>
                <select class="email-form__select" name="users[]" id="users" multiple size="5" required novalidate>
                    <option class="email-form__select-placeholder" value="" disabled selected>個別に選択</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <p class="email-form__error-message">
                @error('users')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="email-form__group">
            <div class="email-form__input-container">
                <i class="fa-solid fa-tag fa-xl"></i>
                <select class="email-form__select" name="roles" id="roles" required novalidate>
                    <option value="" disabled selected>ロールで選択</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <p class="email-form__error-message">
                @error('roles')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="email-form__group">
            <div class="email-form__input-container">
                <i class="fa-solid fa-envelope fa-xl"></i>
                <input class="email-form__input" type="text" name="subject" placeholder="件名" required>
            </div>
            <p class="email-form__error-message">
                @error('subject')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="email-form__group">
            <div class="email-form__input-container">
                <i class="fa-solid fa-pen fa-xl"></i>
                <textarea class="email-form__textarea" name="message" placeholder="メッセージ" required></textarea>
            </div>
            <p class="email-form__error-message">
                @error('message')
                {{ $message }}
                @enderror
            </p>
        </div>

        <div class="email-form__button-container">
            <button class="email-form__button" type="submit">送信</button>
            <button class="email-form__button" type="button" onclick="location.reload();">リロード</button>
        </div>
    </form>
</div>


@endsection