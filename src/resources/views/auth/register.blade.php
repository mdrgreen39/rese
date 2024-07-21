@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('modal')

<label class="modal-button" for="check">
    <span></span>
</label>
<input class="modal-toggle" type="checkbox" id="check">
<div class="modal">
    <div class="modal-content">
        <label class="modal-content__close" for="check"></label>
        <ul class="modal-list">
            <li class="modal-list__item"><a class="modal-list__link" href="/login">Login</a></li>
        </ul>
    </div>

</div>


@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">Registration</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="/register" method="post" novalidate>
            @csrf
            <div class="register-form__group">
                <div class="register-form__input-container">
                    <img class="register-form__user-icon" src="/images/icons/icon_user.svg" alt="user_icon">
                    <input class="register-form__input" type="text" name="name" id="name" placeholder="Username" value="{{ old('name') }}">

                </div>


                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <div class="register-form__input-container">
                    <img class="register-form__email-icon" src="/images/icons/icon_email.svg" alt="email_icon">
                    <input class="register-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                </div>

                <p class="register-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <div class="register-form__input-container">
                    <img class="register-form__password-icon" src="/images/icons/icon_password.svg" alt="password_icon">
                    <input class="register-form__input" type="password" name="password" id="password" placeholder="Password">
                </div>

                <p class="register-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__button-container">
                <button class="register-form__button" type="submit">登録</button>
            </div>


        </form>
    </div>
</div>


@endsection