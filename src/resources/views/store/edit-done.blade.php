@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/edit-done.css') }}">
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

<div class="edit-done">
    <h2 class="edit-done__heading">店舗情報が更新されました</h2>
    <div class="edit-done__button-container">
        <a class="edit-done__button" href="{{ route('store.mypage') }}">戻る</a>
    </div>
</div>


@endsection