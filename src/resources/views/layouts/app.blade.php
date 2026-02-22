<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flea Market</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body>

    {{-- 認証表示（デバッグ兼） --}}
    <div class="app-authbar">
        @auth
            ログイン中：{{ auth()->user()->email }}
        @else
            未ログイン（guest）
        @endauth
    </div>

    {{-- ログアウト --}}
    @auth
        <form method="POST" action="{{ route('logout') }}" class="app-logout">
            @csrf
            <button type="submit" class="app-logout__btn">ログアウト</button>
        </form>
    @endauth

    <main class="app-main">

        @if (session('warning'))
            <div class="app-flash app-flash--warning">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('success'))
            <div class="app-flash app-flash--success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>
