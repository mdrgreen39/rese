@component('mail::message')

{!! nl2br(e($messageContent)) !!}

@component('mail::button', ['url' => url('/login')])
ログインする
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent