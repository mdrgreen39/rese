@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
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

<div class="done-form">
    <h2 class="done-form__heading">ご予約ありがとうございます</h2>
    <p class="done-form__text">デポジットを支払うことができます</p>
    <div class="done-form__button-container">
        <form class="done-form__button-form" action="{{ route('reservation.payDeposit', ['reservation_id' => $reservation->id]) }}" method="post">
            @csrf
            <button class="done-form__button" type="submit">支払う</button>
        </form>

        <a class="done-form__button" href="{{ route('shops.index') }}">戻る</a>

    </div>
</div>


@endsection