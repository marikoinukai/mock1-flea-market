@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>ログイン</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label>Email</label><br>
                <input type="email" name="email" required autofocus>
            </div>

            <div style="margin-top:10px;">
                <label>Password</label><br>
                <input type="password" name="password" required>
            </div>

            {{-- <div style="margin-top:10px;">
                <label>
                    <input type="checkbox" name="remember">
                    ログイン状態を保持
                </label>
            </div> --}}

            <button style="margin-top:14px;">ログイン</button>
        </form>
    </div>
@endsection
