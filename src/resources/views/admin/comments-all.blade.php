@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/comments-all.css') }}">
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

<div class="comments-all">
    @if (session('message'))
    <div class="flash-message">
        {{ session('message') }}
    </div>
    @endif
    <div class="comments-all-heading">
        <a class="comments-all-heading__button-before" href="{{ route('admin.dashboard') }}">&lt;</a>
        <h2 class="comments-all-heading__title">口コミ一覧</h2>
        @if (session('message'))
        <div class="flash-message">
            {{ session('message') }}
        </div>
        @endif
    </div>
    <div class="comments-all-list">
        @if ($comments->isEmpty())
        <p class="no-results__text">口コミはありません</p>
        @else
        <table class="comments-table">
            <thead>
                <tr>
                    <th>コメントID</th>
                    <th>ユーザー名</th>
                    <th>コメント内容</th>
                    <th>評価</th>
                    <th>投稿日時</th>
                    <th>店舗名</th>
                    <th>画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->user->name }}</td>
                    <td>{{ Str::limit($comment->comment, 50) }}</td> <!-- 文字数制限して表示 -->
                    <td>{{ $comment->rating }} ★</td>
                    <td>{{ \Carbon\Carbon::parse($comment->created_at)->format('Y/m/d H:i') }}</td>
                    <td>{{ $comment->shop->name }}</td>
                    <td>
                        @if ($comment->image)
                        <img src="{{ Storage::disk('public')->url($comment->image) }}" alt="Image Preview" class="image-preview" width="50">
                        @else
                        なし
                        @endif
                    </td>
                    <td>
                        <!-- 削除フォーム -->
                        <form action="{{ route('admin.destroyComments', ['comment' => $comment->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-link" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{ $comments->appends(request()->query())->links('vendor.pagination.custom') }}
</div>

@endsection