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
        <div class="comment-area">
            <a href="{{ route('comment.form', ['shop_id' => $shop->id]) }}" class="comment-area__comment-form--link">口コミを投稿する</a>
            @if (session('error'))
            <div class="comment-area__error-message">
                {{ session('error') }}
            </div>
            @endif
            <div class="comment-area__button-container">
                <button id="showAllCommentsButton" class="comment-area__button--display">全ての口コミ情報</button>
            </div>
            <div class="comment-content">
                <div class="latest-comment">
                    @if($latestComment)
                    <div class="comment-actions">
                        @if($latestComment->user_id === auth()->id())
                        <a href="{{ route('comment.editComment', ['comment' => $latestComment->id, 'shop_id' => $shop->id]) }}" class="edit-link">口コミを編集</a>
                        <form action="{{ route('comment.destroy', $latestComment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-link">口コミを削除</button>
                        </form>
                        @endif
                    </div>
                    @if($latestComment->image)
                    <img src="{{ url('storage/' .  $latestComment->image) }}" alt="Comment Image" class="comment-image-thumbnail">
                    @endif
                    <div class="rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $latestComment->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                    <p class="comment-text">{{ $latestComment->comment }}</p>
                    @else
                    <p class="comment-status-message">まだコメントはありません</p>
                    @endif
                </div>
                <div class="comments-container" id="commentsContainer" style="display:none;">
                    @foreach($allComments as $comment)
                    @if($comment->id !== $latestComment->id)
                    <div class="comment">
                        <div class="comment-actions">
                            @if($comment->user_id === auth()->id())
                            <a href="{{ route('comment.editComment', ['comment' => $latestComment->id, 'shop_id' => $shop->id]) }}" class="edit-link">口コミを編集</a>

                            <form action="{{ route('comment.destroy', ['comment' => $comment->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-link">口コミを削除</button>
                            </form>
                            @endif
                        </div>
                        @if($comment->image)
                        <img src="{{ url('storage/' . $comment->image) }}" alt="Comment Image" class="comment-image-thumbnail">
                        @endif
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $comment->rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                        <p>{{ $comment->comment }}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
                <script>
                    document.getElementById('showAllCommentsButton').addEventListener('click', function() {
                        const commentsContainer = document.getElementById('commentsContainer');
                        commentsContainer.style.display = commentsContainer.style.display === 'none' ? 'block' : 'none';
                    });
                </script>
            </div>
        </div>
    </div>

    @livewire('reservation-form', ['shop' => $shop, 'times' => $times, 'numberOfPeople' => $numberOfPeople])

</div>

@endsection