@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('nav')
@auth
@role('admin')
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('admin.register') }}">Admin Register</a></li>
    </ul>
</nav>
@else

@role('store_manager')
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('store.mypage') }}">Store Page</a></li>
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('store.register') }}">Store Registration</a></li>
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('reservation.checkin') }}">来店確認</a></li>
    </ul>
</nav>
@else

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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('user.mypage') }}">Mypage</a></li>
    </ul>
</nav>
@endrole
@endrole

@else
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
@endauth

@endsection
@section('search')


@livewire('shop-search')

@endsection

@section('content')

<div class="ebug-content">



</div>

@php
// デフォルト値を設定
$searchTerm = $searchTerm ?? '';
$prefectureId = $prefectureId ?? '';
$genreId = $genreId ?? '';
@endphp

@endphp


<!-- 検索条件がある場合 -->
@livewire('shop-search-results', [
'prefectureId' => $prefectureId,
'genreId' => $genreId,
'searchTerm' => $searchTerm,
])






@endsection