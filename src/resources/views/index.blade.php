@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
@section('search')
<div class="header-search-container">
    <form class="header-search-form" action="/search" method="get">
        @csrf
        <div class="header-search__select-group">
            <div class="header-search__select-wrapper">
                <select class="header-search__select" name="prefecture_id" id="prefecture_id">
                    <option class=>All area</option>
                    @foreach($prefectures as $prefecture)
                    <option value="{{ $prefecture->id }}">{{ $prefecture->name }}</option>
                    @endforeach
                </select>
                <img class="header-search__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>
            <div class="header-search__select-wrapper">
                <select class="header-search__select" name="genre_id" id="genre_id">
                    <option>All genre</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
                <img class="header-search__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>
        </div>

        <div class="header-search__input-group">
            <button class="header-search__button" type="submit"><img class="header-search__button-icon" src="/images/icons/icon_search.svg" alt="search_icon"></button>
            <input class="header-search__input" type="text" name="search" placeholder="Search ...">
        </div>

    </form>
</div>



@endsection

@section('content')

<div class="shop">
    <div class="shop-container">
        <div class="shop-wrap">
            @foreach($shops as $shop)
            <div class="shop-wrap__item">
                <img class="shop-wrap__item-photo" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">{{ $shop->name }}</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                        <li class="shop-wrap__item-tag">#{{ $shop->genre->name }}</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart-{{ $shop->id }}">
                        <label class="shop-wrap__item-heart" for="toggle-heart-{{ $shop->id }}"></label>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

@endsection