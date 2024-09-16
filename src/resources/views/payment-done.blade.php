@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment-done.css') }}">
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

<div class="payment-done-form">
    <h2 class="payment-done-form__heading">支払いが完了しました</h2>
    <div class="payment-done-form__button-container">
        <a class="payment-done-form__button" href="{{ route('shops.index') }}">戻る</a>
    </div>
</div>


@endsection