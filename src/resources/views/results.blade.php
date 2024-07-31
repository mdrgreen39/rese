@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/results.css') }}">
@endsection

@section('nav')
@auth
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
        <li class="header-nav__item"><a class="header-nav__link" href="">Mypage</a></li>
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
        <li class="header-nav__item"><a class="header-nav__link" href="/register">Registration</a></li>
        <li class="header-nav__item"><a class="header-nav__link" href="/login">Login</a></li>
    </ul>
</nav>
@endauth

@endsection


@section('content')

<div class="result">
    <div class="result-container">
        <p class="result__heading">検索結果</p>

        @if($shops->isEmpty())
        <p class="result__text">該当する店舗が見つかりませんでした</p>
        @else
        <div class="result-wrap">
            @foreach($shops as $shop)
            <div class="result-wrap__item">
                <img class="result-wrap__item-photo" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}">
                <div class="result-wrap__item-content">
                    <h2 class="result-wrap__item-name">{{ $shop->name }}</h2>
                    <ul class="result-wrap__item-tag-container">
                        <li class="result-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                        <li class="result-wrap__item-tag">#{{ $shop->genre->name }}</li>
                    </ul>

                    <div class="result-wrap__item-container">
                        <a class="result-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>
                        <input class="result-wrap__item-toggle-heart" type="checkbox" id="toggle-heart-{{ $shop->id }}">
                        <label class="result-wrap__item-heart" for="toggle-heart-{{ $shop->id }}"></label>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
        @endif

        <div class="result__button-container">
            <a class="result__button" href="{{route('shops.index') }}">トップへ戻る</a>
        </div>


    </div>
</div>

@endsection