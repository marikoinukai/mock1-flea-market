@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <h1 class="auth-title">会員登録</h1>

            <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
                @csrf

                {{-- ユーザー名 --}}
                <div class="auth-field">
                    <label for="name" class="auth-label">ユーザー名</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="ui-input auth-input" autofocus>
                    @error('name')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- メールアドレス --}}
                <div class="auth-field">
                    <label for="email" class="auth-label">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="ui-input auth-input">
                    @error('email')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="auth-field">
                    <label for="password" class="auth-label">パスワード</label>
                    <input id="password" type="password" name="password" class="ui-input auth-input">
                    @error('password')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 確認用パスワード --}}
                <div class="auth-field">
                    <label for="password_confirmation" class="auth-label">確認用パスワード</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="ui-input auth-input">
                    @error('password_confirmation')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">登録する</button>

                <div class="auth-links">
                    <a href="{{ route('login') }}" class="auth-link">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
@endsection