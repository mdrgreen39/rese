@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
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

@endauth

@endsection

@section('content')

<div class="mypage">
    <div class="mypage-heading">
        <h2 class="mypage-heading__name">testさん</h2>
    </div>
    <div class="mypage-reserve-container">
        <h3 class="mypage-reserve__heading">予約状況</h3>
        <div class="mypage-reserve__confirm">
            <div class="mypage-reserve__confirm-title">
                <img src="" alt="">
                <p class="">予約1</p>
                <input type="checkbox">
                <label for="">
                    <span></span>
                </label>
            </div>
            <table class="mypage-reserve__confirm-table">
                <tr>
                    <th>Shop</th>
                    <td>仙人</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>2021-04-01</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>17:00</td>
                </tr>
                <tr>
                    <th>Number</th>
                    <td>1人</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="shop">
        <h3 class="mypage-reserve__heading">予約状況</h3>
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


</div>


@endsection