@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
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
    </ul>
</nav>

@endsection

@section('content')

<div class="admin-dashboard-menu">
    <ul>
        <li><a class="admin-dashboard-link" href="{{ route('admin.ownerRegister') }}">店舗代表者登録</a></li>
        <li><a class="admin-dashboard-link" href="{{ route('admin.emailNotification') }}">メール送信</a></li>
        <li><a class="admin-dashboard-link" href="{{ route('admin.showComments') }}">口コミ一覧</a></li>
    </ul>
</div>

@endsection