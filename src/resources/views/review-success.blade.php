@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review-success.css') }}">
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

<div class="review-form">
    <h2 class="review-form__heading">{{ $shop->name }}へのレビューを投稿完了しました</h2>
    <p class="review-form__text">ご意見をお寄せいただきありがとうございます</p>
    <div class="review-form__button-container">
        <a class="review-form__button" href="{{ route('shops.index') }}">戻る</a>
    </div>
</div>


@endsection