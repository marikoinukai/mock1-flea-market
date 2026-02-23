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
    <header class="app-header">
        <div class="app-header__inner">
            <a href="{{ route('items.index') }}" class="app-header__logo">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="app-header__logoImg">
            </a>

            <form action="{{ route('items.index') }}" method="GET" class="app-header__search">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？"
                    class="ui-input app-header__searchInput">
                <button type="submit" class="ui-button app-header__searchBtn">検索</button>
            </form>

            <nav class="app-header__nav">
                @auth

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-header__link">
                            ログアウト
                        </button>
                    </form>

                    <a href="{{ route('profile.edit') }}" class="app-header__user">
                        <span class="app-header__userText">マイページ</span>
                        {{-- <button type="submit" class="ui-button app-header__searchBtn">出品</button> --}}
                    </a>
                    {{-- 出品（リンクにする） --}}
                    <a href="{{ route('items.index') }}" class="ui-button ui-button--primary app-header__navBtn">
                        出品
                    </a>
                @else
                    <a href="{{ route('login') }}" class="app-header__link">ログイン</a>
                    <a href="{{ route('register') }}" class="app-header__link">会員登録</a>
                @endauth
            </nav>
        </div>
    </header>

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
