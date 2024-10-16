@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/checkin.css') }}">
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

<div class="checkin">
    @if (session('success'))
    <h2 class="checkin__heading">{{ session('success') }}</h2>
    @elseif (session('error'))
    <h2 class="checkin__heading">{{ session('error') }}</h2>
    @elseif (session('info'))
    <h2 class="checkin__heading">{{ session('info') }}</h2>
    @else
    <h2 class="checkin__heading">メッセージがありません</h2>
    @endif

    <div class="checkin__button-container">
        <a class="checkin__button" href="{{ route('store.dashboard') }}">戻る</a>
    </div>
</div>

@endsection