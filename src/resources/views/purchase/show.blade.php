@extends('layouts.app')

@section('content')
    <h1>購入画面</h1>
    {{-- 左：商品情報 --}}
    <div class="purchase-left">
        <div class="purchase-item">
            @if ($item->image)
                <img src="{{ asset('img/' . $item->image->image_path) }}" alt="商品画像" class="purchase-item-image">
            @else
                <div class="purchase-item-image-empty">画像なし</div>
            @endif

            <div class="purchase-item-info">
                <p class="purchase-item-title">{{ $item->title }}</p>
                <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <hr>

        {{-- 支払い方法 --}}
        <div class="purchase-payment">
            <h2>支払い方法</h2>

            <form method="GET" action="{{ route('purchase.show', $item) }}">
                <select name="payment_method" class="purchase-payment-select">
                    <option value="">選択してください</option>
                    @foreach ($payments as $key => $label)
                        <option value="{{ $key }}" {{ request('payment_method') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">更新</button>
            </form>
        </div>

        <hr>

        {{-- 配送先（今はダミー表示。User住所カラムを作ったら差し替え） --}}
        <div class="purchase-shipping">
            <h2>配送先</h2>
            <p>〒 {{ $shipping['postal_code'] ?? $user->postal_code }}</p>
            <p>
                {{ $shipping['address_line1'] ?? $user->address_line1 }}
                {{ $shipping['address_line2'] ?? ($user->address_line2 ?? '') }}
            </p>
        </div>
    </div>

    {{-- 右：まとめ --}}
    <div class="purchase-right">
        <div class="purchase-summary">
            <p>商品代金： ¥{{ number_format($item->price) }}</p>

            <p>
                支払い方法：
                {{ $payments[request('payment_method')] ?? '未選択' }}
            </p>
        </div>

        {{-- 購入用（POST） --}}
        <form method="POST" action="{{ route('purchase.store', $item) }}">
            @csrf
            <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">

            <button type="submit" class="purchase-submit">
                購入する（確認）
            </button>
        </form>
    </div>
@endsection
