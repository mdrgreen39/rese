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

@php
// デフォルト値を設定
$searchTerm = $searchTerm ?? '';
$prefectureId = $prefectureId ?? '';
$genreId = $genreId ?? '';
@endphp


@livewire('shop-search')

@endsection

@section('content')

@section('content')

@php
// デフォルト値を設定
$searchTerm = $searchTerm ?? '';
$prefectureId = $prefectureId ?? '';
$genreId = $genreId ?? '';
@endphp



@if($shops->isEmpty())



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
@else
@livewire('shop-search-results', [
'prefectureId' => $prefectureId ?? '',
'genreId' => $genreId ?? '',
'searchTerm' => $searchTerm ?? '',
])

@endif

@endsection