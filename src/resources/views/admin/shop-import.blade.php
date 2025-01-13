@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop-import.css') }}">
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

@if (session('success'))
<div class="shop-import__session-message">
    {{ session('success') }}
</div>
@endif

<div class="shop-import">
    <h2 class="shop-import__heading">CSVインポート</h2>
    <form class="shop-import__form" action="{{ route('admin.shops.import.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="shop-import__group">
            <label for="csv">CSVファイルを選択:</label>
            <input type="file" name="csv" id="csv" accept=".csv" required>
        </div>
        @if ($errors->has('csv'))
        <p class="shop-import__error-message">{{ $errors->first('csv') }}</p>
        @endif
        <div class="shop-import__button-container">
            <button class="shop-import__button" type="submit">インポート</button>
        </div>
    </form>
</div>

@endsection