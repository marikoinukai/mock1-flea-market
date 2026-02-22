@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2>プロフィール設定</h2>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- アイコン表示 --}}
            <div class="profile-icon">
                @if ($user->icon_path)
                    <img src="{{ asset('storage/' . $user->icon_path) }}" alt="icon" class="profile-icon__img">
                @else
                    <div class="profile-icon__placeholder"></div>
                @endif
            </div>

            {{-- アイコン選択 --}}
            <div class="profile-field">
                <label class="profile-label">アイコン画像</label>
                <input type="file" name="icon" accept="image/*" class="profile-input-file">

                @error('icon')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>ユーザー名</label><br>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div>
                <label>郵便番号</label><br>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            </div>

            <div>
                <label>住所</label><br>
                <input type="text" name="address_line1" value="{{ old('address_line1', $user->address_line1) }}">
            </div>

            <div>
                <label>建物名・部屋番号</label><br>
                <input type="text" name="address_line2" value="{{ old('address_line2', $user->address_line2) }}">
            </div>

            <button>更新する</button>
        </form>
    </div>
@endsection
