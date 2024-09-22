@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store/reservations.css') }}">
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
        <li class="header-nav__item"><a class="header-nav__link" href="{{ route('store.dashboard') }}">店舗用管理画面</a></li>
    </ul>
</nav>


@endsection


@section('content')
<div class="reservation">
    <div class="reservation-heading">
        <h2 class="reservation-heading__text">{{ $shop->name }}の予約リスト</h2>
    </div>
    <div class="reservation-list">
        @if ($reservations->isEmpty())
        <p>予約はありません</p>
        @else
        <table class="reservation-table">
            <thead>
                <tr>
                    <th>予約日</th>
                    <th>予約時間</th>
                    <th>お客様名</th>
                    <th>予約人数</th>
                    <th>来店状態</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>

                    <td>{{ \Carbon\Carbon::parse($reservation->date)->format('Y/m/d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ $reservation->people }}</td>
                    <td>@if ($reservation->isCheckedIn())
                        <span>来店済み</span>
                        @else
                        <span>ー</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $reservations->appends(request()->query())->links('vendor.pagination.custom') }}

        @endif
    </div>


    @endsection