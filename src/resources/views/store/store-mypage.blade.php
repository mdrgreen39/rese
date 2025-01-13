@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/store-mypage.css') }}">
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

<div class="store-mypage">

    <div class="store-mypage-heading">
        <a class="store-mypage-heading__button" href="{{ route('store.register') }}">店舗登録</a>
    </div>

    <div class="store-mypage-content">
        @if(count($shops) > 0)
        <div class="store-mypage-container">
            <div class="store-mypage-wrap">
                @foreach($shops as $shop)
                <div class="store-mypage-wrap__item">
                    <img class="store-mypage-wrap__item-photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                    <div class="store-mypage-wrap__item-content">
                        <h2 class="store-mypage-wrap__item-name">{{ $shop->name }}</h2>
                        <ul class="store-mypage-wrap__item-tag-container">
                            <li class="store-mypage-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                            <li class="store-mypage-wrap__item-tag">#{{ $shop->genre->name }}</li>
                        </ul>
                        <div class="store-mypage-wrap__item-container">
                            <a class="store-mypage-wrap__item-button" href="{{ route('store.detail', $shop->id) }}">詳細</a>
                            <a class="store-mypage-wrap__item-button" href="{{ route('store.reservation', $shop->id) }}">予約確認</a>
                            <a class="store-mypage-wrap__item-button" href="{{ route('store.edit', $shop->id) }}">編集</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="no-results__text">該当する店舗が見つかりませんでした</p>
        </div>
        @endif
    </div>
</div>

@endsection