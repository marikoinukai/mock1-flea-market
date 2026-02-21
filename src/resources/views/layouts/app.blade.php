<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flea Market</title>
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
        @yield('content')
    </main>

</body>

</html>
