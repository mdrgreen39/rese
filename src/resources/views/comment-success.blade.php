@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/comment-done.css') }}">
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

<div class="comment">
    <h2 class="comment__heading">コメントが投稿されました</h2>
    <div class="comment__button-container">
        <a class="comment__button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">戻る</a>
    </div>
</div>

@endsection