@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/owner-register-done.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('admin.dashboard') }}">管理画面</a></li>
    </ul>
</nav>

@endsection

@section('content')

<div class="owner-register-done">
    <h2 class="owner-register-done__heading">店舗代表者を登録しました</h2>
    <div class="owner-register-done__button-container">
        <a class="owner-register-done__button" href="{{ route('admin.dashboard') }}">戻る</a>
    </div>
</div>

@endsection