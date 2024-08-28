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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('user.mypage') }}">Mypage</a></li>
    </ul>
</nav>


@endsection

@section('content')

<div class="checkin-form">
    @if (session('status'))
    @php
    $status = session('status');
    @endphp

    @if ($status['type'] == 'success')
    <h2 class="checkin-form__heading"> {{ $status['message'] }}</h2>

    @if ($status['type'] == 'error')
    <h2 class="checkin-form__heading"> {{ $status['message'] }}</h2>

    @if ($status['type'] == 'info')
    <h2 class="checkin-form__heading"> {{ $status['message'] }}</h2>

    <div class="checkin-form__button-container">
        <a class="checkin-form__button" href="{{ route('shops.index') }}">戻る</a>
    </div>
</div>


@endsection