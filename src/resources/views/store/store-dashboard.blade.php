@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/store-dashboard.css') }}">
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
    </ul>
</nav>

@endsection

@section('content')

<div class="store-dashboard-menu">
    <ul>
        <li><a class="store-dashboard-link" href="{{ route('store.mypage') }}">登録店舗一覧</a></li>
        <li><a class="store-dashboard-link" href="{{ route('store.register') }}">店舗登録</a></li>
    </ul>
</div>

@endsection