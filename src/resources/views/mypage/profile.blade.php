@extends('layouts.app')

@section('content')
    <div class="auth-page">
        <h1 class="auth-title">プロフィール設定</h1>

        <div class="auth-card">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                class="auth-form profile-form">
                @csrf
                @method('PUT')

                {{-- アイコン --}}
                <div class="profile-icon-area">
                    <div class="profile-icon">
                        @if ($user->icon_path)
                            <img id="icon-preview" src="{{ asset('storage/' . $user->icon_path) }}" alt="icon"
                                class="profile-icon__img">
                        @else
                            <img id="icon-preview" alt="icon" class="profile-icon__img is-hidden">
                            <div class="profile-icon__placeholder"></div>
                        @endif
                    </div>

                    <div class="profile-icon-upload">
                        <label class="profile-button">
                            画像を選択する
                            <input id="icon-input" type="file" name="icon" accept="image/*"
                                class="profile-input-file">
                        </label>

                        @error('icon')
                            <p class="auth-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ユーザー名 --}}
                <div class="auth-field profile-field">
                    <label class="auth-label profile-label">ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="auth-input ui-input">
                    @error('name')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 郵便番号 --}}
                <div class="auth-field profile-field">
                    <label class="auth-label profile-label">郵便番号</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                        placeholder="123-4567" class="auth-input ui-input">
                    @error('postal_code')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 住所 --}}
                <div class="auth-field profile-field">
                    <label class="auth-label profile-label">住所</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1', $user->address_line1) }}"
                        class="auth-input ui-input">
                    @error('address_line1')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 建物名 --}}
                <div class="auth-field profile-field">
                    <label class="auth-label profile-label">建物名</label>
                    <input type="text" name="address_line2" value="{{ old('address_line2', $user->address_line2) }}"
                        class="auth-input ui-input">
                    @error('address_line2')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">
                    更新する
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}" defer></script>
@endpush
