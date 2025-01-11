@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop-detail.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('user.mypage') }}">Mypage</a></li>
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

<div class="detail">
    <div class="detail-container">
        <div class="detail-heading">
            <a class="detail-heading__button-before" href="{{ route('shops.index') }}">&lt;</a>
            <h2 class="detail-heading__name">{{ $shop->name }}</h2>
        </div>
        <img class="detail-container__photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
        <div class="detail-container__content">
            <ul class="detail-container__item">
                <li class="detail-container__item-tag">#{{ $shop->prefecture->name }}</li>
                <li class="detail-container__item-tag">#{{ $shop->genre->name }}</li>
            </ul>
            <p class="detail-container__description">{{ $shop->description }}</p>
        </div>
        @if($canReview)

        @livewire('review-form', ['shop' => $shop])

        @endif
        <a href="{{ route('comment.form', ['shop_id' => $shop->id]) }}" class="detail-container__comment">口コミを投稿する</a>

    </div>

    @livewire('reservation-form', ['shop' => $shop, 'times' => $times, 'numberOfPeople' => $numberOfPeople])

</div>

@endsection