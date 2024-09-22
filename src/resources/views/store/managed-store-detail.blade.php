@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/managed-store-detail.css') }}">
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

@if (session('message'))
<div class="store-detail__session-message">

    {!! session('message') !!}

</div>
@endif

<div class="store-detail">
    <div class="store-detail-container">
        <div class="store-detail-heading">
            <a class="store-detail-heading__button-before" href="{{ route('store.mypage') }}">&lt;</a>
            <h2 class="store-detail-heading__name">{{ $shop->name }}</h2>
        </div>
        <img class="store-detail-container__photo" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}">
        <div class="store-detail-container__content">
            <ul class="store-detail-container__item">
                <li class="store-detail-container__item-tag">#{{ $shop->prefecture->name }}</li>
                <li class="store-detail-container__item-tag">#{{ $shop->genre->name }}</li>
            </ul>
            <p class="store-detail-container__description">{{ $shop->description }}</p>
        </div>
        <div class="store-detail-container__wrap">
            <a class="store-detail-container__button" href="{{ route('store.reservation', $shop->id) }}">予約確認</a>
            <a class="store-detail-container__button" href="{{ route('store.edit', $shop->id) }}">編集</a>
        </div>

    </div>




</div>