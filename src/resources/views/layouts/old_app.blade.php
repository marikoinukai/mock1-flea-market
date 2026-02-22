<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flea Market</title>

    {{-- 共通CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>


<div style="padding:10px; background:#f6f6f6; margin-bottom:10px;">
    @auth
        ログイン中：{{ auth()->user()->email }}
    @else
        未ログイン（guest）
    @endauth
</div>





<body>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
    {{-- ここに各画面の @section('content') が差し込まれる --}}
    <main>

        @if (session('warning'))
            <div style="margin:12px 0; padding:10px; border:1px solid #f59e0b; background:#fff7ed; color:#b45309;">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('success'))
            <div style="margin:12px 0; padding:10px; border:1px solid #22c55e; background:#f0fdf4; color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>
