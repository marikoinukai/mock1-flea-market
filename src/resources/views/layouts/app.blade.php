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
            <a href="{{ route('items.index') }}" class="app-header__logo">Flea Market</a>

            <form action="{{ route('items.index') }}" method="GET" class="app-header__search">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？"
                    class="ui-input app-header__searchInput">
                <button type="submit" class="ui-button app-header__searchBtn">検索</button>
            </form>

            <nav class="app-header__nav">
                @auth
                    <a href="{{ route('profile.edit') }}" class="app-header__user">
                        @if (auth()->user()->icon_path)
                            <img src="{{ asset('storage/' . auth()->user()->icon_path) }}" alt="icon"
                                class="app-header__avatar">
                        @else
                            <span class="app-header__avatar app-header__avatar--placeholder"></span>
                        @endif
                        <span class="app-header__userText">マイページ</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="app-header__logout">
                        @csrf
                        <button type="submit" class="ui-button ui-button--primary">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="app-header__link">ログイン</a>
                    <a href="{{ route('register') }}" class="app-header__link app-header__link--primary">会員登録</a>
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
