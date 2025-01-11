@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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

<div class="mypage">
    <div class="mypage-heading">
        <h2 class="mypage-heading__name">{{ Auth::user()->name }}さん</h2>
    </div>
    <div class="mypage-content">

        @livewire('reservation-list')

        <div class="mypage-favorite">
            <h3 class="mypage-favorite__heading">お気に入り店舗</h3>

            @if($favoriteShops->isEmpty())
            <p class="no-results__text">お気に入りの店舗が登録されていません</p>
            <p class="no-results__text">HOMEより店舗のハートを押すと「お気に入り」に登録できます</p>
            @else
            <div class="mypage-favorite-container">
                <div class="mypage-favorite-wrap">
                    @foreach($favoriteShops as $shop)
                    <div class="mypage-favorite-wrap__item">
                        <img class="mypage-favorite-wrap__item-photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                        <div class="mypage-favorite-wrap__item-content">
                            <h3 class="mypage-favorite-wrap__item-name">{{ $shop->name }}</h3>
                            <ul class="mypage-favorite-wrap__item-tag-container">
                                <li class="mypage-favorite-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                                <li class="mypage-favorite-wrap__item-tag">#{{ $shop->genre->name }}</li>
                            </ul>

                            <div class="mypage-favorite-wrap__item-container">
                                <a class="mypage-favorite-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>

                                @livewire('favorite-toggle', ['shop' => $shop], key('favorite-toggle-' . $shop->id))

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection