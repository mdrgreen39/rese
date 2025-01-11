@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/comment.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('user.mypage') }}">Mypage</a></li>
    </ul>
</nav>

@endsection

@section('content')

<div class="comment">
    <form wire:submit.prevent="submit" enctype="multipart/form-data" novalidate>
        <div class="comment-content">
            <!-- 左側の店舗情報 -->
            <div class="comment-left">
                <div class="comment-shop">
                    <h2 class="comment-shop__heading">今回のご利用はいかがでしたか</h2>

                    <div class="comment-shop-container">

                        <div class="comment-shop-wrap__item">
                            <img class="comment-shop-wrap__item-photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                            <div class="comment-shop-wrap__item-content">
                                <h3 class="comment-shop-wrap__item-name">{{ $shop->name }}</h3>
                                <ul class="comment-shop-wrap__item-tag-container">
                                    <li class="comment-shop-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                                    <li class="comment-shop-wrap__item-tag">#{{ $shop->genre->name }}</li>
                                </ul>
                                <div class="comment-shop-wrap__item-container">
                                    <a class="comment-shop-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>

                                    @livewire('favorite-toggle', ['shop' => $shop], key('favorite-toggle-' . $shop->id))

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 右側の評価フォーム -->
            <div class="comment-right">



                <div class="comment-form">
                    <!-- 星評価 -->
                    <div class="comment-form__group">
                        <p class="comment-form__group-text">体験を評価してください</p>
                        <div class="stars">
                            @foreach (range(1, 5) as $value)
                            <span class="star {{ $value <= $rating ? 'selected' : '' }}" data-value="{{ $value }}" wire:click="setRating({{ $value }})">★</span>

                            @endforeach
                        </div>
                    </div>
                    @if ($errors->has('rating'))
                    <p class="comment-form__error-message">{{ $errors->first('rating') }}</p>
                    @endif

                    <!-- 口コミ入力フォーム -->
                    <div class="comment-form__group">
                        <label for="comment" class="comment-form__group-text">口コミを投稿</label>
                        <textarea
                            id=" comment"
                            name="comment"
                            placeholder="カジュアルな夜のお出かけにおすすめのスポット"
                            maxlength="400"
                            wire:model="comment"></textarea>
                        <div class="comment-form__char-count">{{ strlen($comment) }}/400(最高文字数)</div>
                    </div>
                    @if ($errors->has('comment'))
                    <p class="comment-form__error-message">{{ $errors->first('comment') }}</p>
                    @endif

                    <!-- 画像追加 -->
                    <div class="comment-form__group">
                        <label for="image-upload" class="comment-form__group-text">画像の追加</label>
                        <div class="image-upload-box">
                            <input
                                type="file"
                                id="image-upload"
                                wire:model="image"
                                accept="image/jpeg,image/png" />
                            <p>クリックして写真を追加<br><span class="drag-drop">またはドラッグアンドドロップ</span></p>

                        </div>
                        @if ($image)
                        <p>画像が選択されました: {{ $image->getClientOriginalName() }}</p>
                        @endif
                    </div>
                </div>
                @if ($errors->has('image'))
                <p class="comment-form__error-message">{{ $errors->first('image') }}</p>
                @endif

            </div>
        </div>
        <!-- 投稿ボタン -->
        <div class="comment-send">
            <button type="submit" class="comment-send__button">口コミを投稿</button>
        </div>



    </form>


</div>



@endsection