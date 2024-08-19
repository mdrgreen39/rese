<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />


    @yield('css')

    @livewireStyles
</head>

<body>
    <header class="header">
        <div class="header__container">

            <h1 class="header__logo">Rese</h1>

        </div>
        @yield('search')



    </header>
    @yield('nav')

    <div class="content">
        @yield('content')
    </div>
    @livewireScripts
</body>

</html>