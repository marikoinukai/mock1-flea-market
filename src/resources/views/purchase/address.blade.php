@extends('layouts.app')

@section('content')
    <div class="address-page">
        <h1 class="address-title">住所の変更</h1>

        @if ($errors->any())
            <div class="ui-alert ui-alert--danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('purchase.address.update', $item) }}" class="address-form">
            @csrf
            @method('PUT')

            <div class="address-field">
                <label>郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $shipping['postal_code'] ?? '') }}">
            </div>

            <div class="address-field">
                <label>住所</label>
                <input type="text" name="address_line1"
                    value="{{ old('address_line1', $shipping['address_line1'] ?? '') }}">
            </div>

            <div class="address-field">
                <label>建物名</label>
                <input type="text" name="address_line2"
                    value="{{ old('address_line2', $shipping['address_line2'] ?? '') }}">
            </div>

            <button type="submit" class="address-submit">更新する</button>
        </form>
    </div>
@endsection
