@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="">Mypage</a></li>
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
            <button class="detail-heading__button-before" type="submit">&lt;</button>
            <h2 class="detail-heading__name">{{ $shop->name }}</h2>
        </div>
        <img class="detail-container__photo" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}">
        <div class="detail-container__content">
            <ul class="detail-container__item">
                <li class="detail-container__item-tag">#{{ $shop->prefecture->name }}</li>
                <li class="detail-container__item-tag">#{{ $shop->genre->name }}</li>
            </ul>
            <p class="detail-container__description">{{ $shop->description }}</p>
        </div>
    </div>

    <div class="reserve-form">
        <h2 class="reserve-form__heading">予約</h2>
        <form class="reserve-form__form" action="" method="" novalidate>
            @csrf
            <div class="reserve-form__group">

                <input class="reserve-form__select-date" type="date" name="date" id="date" max="9999-12-31" value="" required>
                <p class="reserve-form__error-message">
                    @error('date')
                    {{ $message }}
                    @enderror
                </p>
                <div class="reserve-form__select-wrapper">
                    <select class="reserve-form__select-time" type="time" id="time" name="time" required>
                        @foreach ($times as $time)
                        <option value="{{ $time }}">{{ $time }}
                        </option>
                        @endforeach
                    </select>
                    <img class="reserve-form__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
                </div>

                <p class="reserve-form__error-message">
                    @error('reserve-time')
                    {{ $message }}
                    @enderror
                </p>
                <div class="reserve-form__select-wrapper">
                    <select class="reserve-form__select-number" id="number" name="number" required>
                        @foreach ($numberOfPeople as $number)
                        <option value=" {{ $number }} ">{{ $number }}人</option>
                        @endforeach
                    </select>
                    <img class="reserve-form__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
                </div>
                <p class="reserve-form__error-message">
                    @error('number')
                    {{ $message }}
                    @enderror
                </p>

            </div>

            <div class="reserve-form__confirm">
                <table class="reserve-form__confirm-table">
                    <tr>
                        <th>Shop</th>
                        <td>仙人</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>2024-07-30</td>
                    </tr>
                    <tr>
                        <th>TIme</th>
                        <td>17:00</td>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <td>1人</td>
                    </tr>
                </table>

            </div>
            <div class="reserve-form__button-container">
                <button class="reserve-form__button-change" type="submit">変更する</button>

                <button class="reserve-form__button-decide" type="submit">予約する</button>
            </div>

        </form>
    </div>

</div>


@endsection