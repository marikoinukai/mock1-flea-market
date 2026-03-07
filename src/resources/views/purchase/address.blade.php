@extends('layouts.app')

@section('content')
    <div class="address-page">
        <h1 class="address-title">住所の変更</h1>

        <div class="address-card">
            <form method="POST" action="{{ route('purchase.address.update', $item) }}" class="address-form">
                @csrf

                <div class="address-field">
                    <label class="address-label">郵便番号</label>
                    <input type="text" name="postal_code" class="address-input"
                        value="{{ old('postal_code', $shipping['postal_code'] ?? '') }}">
                    @error('postal_code')
                        <p class="address-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-field">
                    <label class="address-label">住所</label>
                    <input type="text" name="address_line1" class="address-input"
                        value="{{ old('address_line1', $shipping['address_line1'] ?? '') }}">
                    @error('address_line1')
                        <p class="address-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="address-field">
                    <label class="address-label">建物名</label>
                    <input type="text" name="address_line2" class="address-input"
                        value="{{ old('address_line2', $shipping['address_line2'] ?? '') }}">
                    @error('address_line2')
                        <p class="address-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="address-submit">更新する</button>
            </form>
        </div>
    </div>
@endsection
