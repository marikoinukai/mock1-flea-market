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

            {{-- ログイン・登録ページではロゴのみ表示 --}}
            @unless (request()->routeIs('login') || request()->routeIs('register'))
                <form action="{{ route('items.index') }}" method="GET" class="app-header__search">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？"
                        class="ui-input app-header__searchInput">
                </form>

                <nav class="app-header__nav">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="app-header__link">
                                ログアウト
                            </button>
                        </form>

                        <a href="{{ route('mypage') }}" class="app-header__user">
                            <span class="app-header__userText">マイページ</span>
                        </a>

                        <a href="{{ route('items.create') }}" class="app-header__navBtn">
                            出品
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="app-header__link">ログイン</a>
                        <a href="{{ route('register') }}" class="app-header__link">会員登録</a>
                    @endauth
                </nav>
            @endunless
        </div>
    </header>

    <main class="app-main">
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
