@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_register.css') }}">
@endsection

@section('nav')

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


@endsection

@section('content')
<div class="update-form">
    @if(session('success'))
    <div class="update-form__success-message">{{ session('success') }}</div>
    @endif

    <h2 class="update-form__heading">店舗情報登録</h2>
    <div class="update-form__inner">
        <form class="update-form__form" action="{{ route('admin.create-shop') }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="update-form__group">
                <div class="update-form__input-container">
                    <label for="name">店名</label>
                    <input class="update-form__input" type="text" name="name" id="name" placeholder="店名を入力してください" value="{{ old('name') }}" required>
                </div>
                <p class="update-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="update-form__group">
                <div class="update-form__input-container">
                    <label for="prefecture">所在地域</label>
                    <select class="update-form__input" name="prefecture_id" id="prefecture_id" placeholder="県名を選択してください" required>
                        @foreach($prefecture as $prefecture)
                        <option value="{{ $prefecture->id }}">{{ $prefecture->name }}></option>
                        @endforeach
                    </select>
                </div>
                <p class="update-form__error-message">
                    @error('prefecture_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="update-form__group">
                <div class="update-form__input-container">
                    <label for="genre_id">ジャンル</label>
                    <select class="update-form__input" name="genre_id" id="genre_id" placeholder="ジャンルを選択してください" required>
                        @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}></option>
                        @endforeach
                    </select>
                </div>
                <p class="update-form__error-message">
                    @error('genre_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="update-form__group">
                <div class="update-form__input-container">
                    <label for="description">店舗概要</label>
                    <textarea class="update-form__input" name="description" id="description" placeholder="概要を500文字以内で入力してください" required></textarea>
                </div>
                <p class="update-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="update-form__group">
                <div class="update-form__input-container">
                    <label for="image">写真</label>
                    <input class="update-form__input" type="file" name="image" id="image" placeholder="写真を添付してください" required>
                </div>
                <p class="update-form__error-message">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="update-form__button-container">
                <button class="update-form__button" type="submit">追加</button>
            </div>
        </form>
    </div>
</div>


@endsection