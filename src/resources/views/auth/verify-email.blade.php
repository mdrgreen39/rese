@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
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

<div class="verification-form">
    <h2 class="verification-form__heading">
        メールアドレスの確認について
    </h2>

    <p class="verification-form__text">
        登録ありがとうございます！
    </p>

    <p class="verification-form__text">
        ログインするにはメールアドレスの確認が必要です。登録したメールアドレスにメールを送信していますので、メール内のリンクをクリックしてください。
    </p>

    <p class="verification-form__text">
        メールが届いていない場合は、以下のボタンをクリックしてメールの再送信をしてください。
    </p>

    <form class="verification-form__resend" action="{{ route('verification.send') }}" method="post">
        @csrf
        <button class="verification-form__resend-button" type="submit">メールを再送信</button>
    </form>
</div>



@endsection