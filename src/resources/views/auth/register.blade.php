@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>会員登録</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label>名前</label><br>
                <input type="text" name="name" required>
            </div>

            <div style="margin-top:10px;">
                <label>Email</label><br>
                <input type="email" name="email" required>
            </div>

            <div style="margin-top:10px;">
                <label>Password</label><br>
                <input type="password" name="password" required>
            </div>

            <div style="margin-top:10px;">
                <label>Password（確認）</label><br>
                <input type="password" name="password_confirmation" required>
            </div>

            <button style="margin-top:14px;">登録</button>
        </form>
    </div>
@endsection
