<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">

    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__container">
            @yield('nav')
            <h1 class="header__logo">Rese</h1>

        </div>

    </header>
    <div class="content">
        @yield('content')
    </div>
</body>

</html>