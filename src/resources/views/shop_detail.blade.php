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

@section('content')

<div class="shop">
    <div class="shop-container">
        <div class="shop-wrap">
            <div class="shop-wrap__item">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>

            <div class="shop-wrap__item" href="">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>

            <div class="shop-wrap__item" href="">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>

            <div class="shop-wrap__item" href="">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>

            <div class="shop-wrap__item" href="">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>


            <div class="shop-wrap__item">
                <img src="https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg" alt="sennin-photo" class="shop-wrap__item-photo">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">仙人</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#東京都</li>
                        <li class="shop-wrap__item-tag">#寿司</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="">詳しく見る</a>
                        <input class="shop-wrap__item-toggle-heart" type="checkbox" id="toggle-heart">
                        <label class="shop-wrap__item-heart" for="toggle-heart"></label>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>

@endsection