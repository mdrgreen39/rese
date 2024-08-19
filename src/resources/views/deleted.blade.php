@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/deleted.css') }}">
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

<div class="delete-form">
    <h2 class="delete-form__heading">予約が削除されました</h2>
    <div class="delete-form__button-container">
        <a class="delete-form__button" href="{{ route('user.mypage') }}">戻る</a>
    </div>
</div>


@endsection