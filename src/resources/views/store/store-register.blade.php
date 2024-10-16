@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/store-register.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('store.dashboard') }}">店舗用管理画面</a></li>
    </ul>
</nav>

@endsection

@section('content')

<div class="store-register-form">
    <h2 class="store-register-form__heading">Store Registration</h2>
    <form class="store-register-form__form" action="{{ route('store.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf

        @include('store.-store-form')

        <div class="store-register-form__button-container">
            <button class="store-register-form__button" type="submit">登録</button>
            <a class="store-register-form__button" href="{{ route('store.mypage') }}">戻る</a>
        </div>
    </form>
</div>

@endsection