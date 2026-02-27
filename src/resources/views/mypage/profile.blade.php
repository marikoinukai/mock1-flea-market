@extends('layouts.app')

@section('content')
    <div class="profile-page">

        <h2 class="profile-title">プロフィール設定</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PUT')

            {{-- =========================
            アイコンエリア
        ========================== --}}
            <div class="profile-icon-area">

                <div class="profile-icon">
                    @if ($user->icon_path)
                        <img src="{{ asset('storage/' . $user->icon_path) }}" alt="icon" class="profile-icon__img">
                    @else
                        <div class="profile-icon__placeholder"></div>
                    @endif
                </div>

                <div class="profile-icon-upload">
                    <label class="profile-button">
                        画像を選択する
                        <input type="file" name="icon" accept="image/*" class="profile-input-file">
                    </label>
                    <p class="profile-hint">※画像は2MB以内 / jpg・png</p>
                    @error('icon')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- =========================
            ユーザー名
        ========================== --}}
            <div class="profile-field">
                <label class="profile-label">ユーザー名</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="profile-input ui-input">
                @error('name')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
            郵便番号
        ========================== --}}
            <div class="profile-field">
                <label class="profile-label">郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"placeholder="123-4567" class="profile-input ui-input">
                @error('postal_code')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
            住所
        ========================== --}}
            <div class="profile-field">
                <label class="profile-label">住所</label>
                <input type="text" name="address_line1" value="{{ old('address_line1', $user->address_line1) }}"
                    class="profile-input ui-input">
                @error('address_line1')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
            建物名
        ========================== --}}
            <div class="profile-field">
                <label class="profile-label">建物名・部屋番号</label>
                <input type="text" name="address_line2" value="{{ old('address_line2', $user->address_line2) }}"
                    class="profile-input ui-input">
                @error('address_line2')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
            送信ボタン
        ========================== --}}
            <button type="submit" class="profile-submit">
                更新する
            </button>

        </form>
    </div>
@endsection
