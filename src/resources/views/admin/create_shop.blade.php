@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_shop.css') }}">
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
                    <select class="update-form__input" name="prefecture_id" id="prefecture_id" placeholder="県名を選択してください" value="{{ old('prefecture_id') }}" required>
                        @foreach($prefecture as $prefecture)
                        <option value="{{ $prefecture->id }}">{{ $prefecture->name }}></option>
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
                    <select class="update-form__input" name="genre_id" id="genre_id" placeholder="ジャンルを選択してください" value="{{ old('genre_id') }}" required>
                        @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}></option>
                    </select>
                </div>
                <p class="update-form__error-message">
                    @error('genre_id')
                    {{ $message }}
                    @enderror
                </p>
                <a class="update-form__button" href="#modal1">ジャンルを追加する</a>
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

<div class="modal1" id="modal">

    <div class="modal-form">
        <a href="#"><label class="modal-form__close" for="check"></label></a>
        <h2 class="modal-form__heading">新しいジャンルを追加</h2>
        <div class="modal-form__inner">

            @if(session('success'))
            <div class="model-form__success-message">{{ session('success') }}</div>
            @endif
            <form class="model-form__form" action="{{ route('genres.store') }}" method="post" novalidate>
                @csrf
                <div class="model-form__group">
                    <div class="model-form__input-container">
                        <label for="genre">ジャンル名</label>
                        <input class="model-form__input" type="text" name="genre" id="genre" placeholder="Genre" value="{{ old('genre') }}" required>
                    </div>

                    <p class="model-form__error-message">
                        @error('genre')
                        {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="genre-form__button-container">
                    <button class="genre-form__button" type="submit">追加</button>
                </div>


            </form>
        </div>
    </div>

</div>



@endsection