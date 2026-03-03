@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <div class="auth-container">

            <h1 class="auth-title">ログイン</h1>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label for="email" class="auth-label">メールアドレス</label>
                    <input type="email" id="email" name="email" class="auth-input" required autofocus>
                </div>

                <div class="auth-field">
                    <label for="password" class="auth-label">パスワード</label>
                    <input type="password" id="password" name="password" class="auth-input" required>
                </div>

                <button type="submit" class="auth-submit">
                    ログインする
                </button>

            </form>

            <div class="auth-register-link">
                <a href="{{ route('register') }}">会員登録はこちら</a>
            </div>

        </div>
    </div>
@endsection
