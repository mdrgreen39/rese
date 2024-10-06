@component('mail::message')

{{ $reservation->user->name }} 様

予約のリマインダーです。

- **日時**: {{ $reservation->date }} {{ substr($reservation->time, 0, 5) }}
- **場所**: {{ $reservation->shop->name }}
- **人数**: {{ $reservation->people }}

当日のご来店をお待ちしております。

@component('mail::button', ['url' => url('/login')])
ログインする
@endcomponent

よろしくお願いいたします。
{{ config('app.name') }}
@endcomponent