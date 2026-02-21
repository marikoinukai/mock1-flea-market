@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>プロフィール設定</h2>

        @if ($errors->any())
            <ul style="color:red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div>
                <label>ユーザー名</label><br>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div style="margin-top:10px;">
                <label>郵便番号</label><br>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            </div>

            <div style="margin-top:10px;">
                <label>住所</label><br>
                <input type="text" name="address_line1" value="{{ old('address_line1', $user->address_line1) }}">
            </div>

            <div style="margin-top:10px;">
                <label>建物名・部屋番号</label><br>
                <input type="text" name="address_line2" value="{{ old('address_line2', $user->address_line2) }}">
            </div>

            <button style="margin-top:14px;">更新する</button>
        </form>
    </div>
@endsection
