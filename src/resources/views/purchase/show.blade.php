@extends('layouts.app')
@section('body_class', 'wide')
@section('content')
    <div class="purchase-page">
        <div class="purchase-wrap">

            {{-- 左カラム --}}
            <div class="purchase-left">
                {{-- 商品 --}}
                <div class="purchase-item">
                    <div class="purchase-item__img">
                        @php
                            // item詳細と同じく、storage/ と http の両対応
                            $path = $item->image->image_path ?? null;
                            $src = $path ? (str_starts_with($path, 'http') ? $path : asset('storage/' . $path)) : null;
                        @endphp

                        @if ($src)
                            <img src="{{ $src }}" alt="商品画像">
                        @else
                            {{-- 画像が無い時はグレー枠 --}}
                        @endif
                    </div>

                    <div class="purchase-item__meta">
                        <h1 class="purchase-item__title">
                            {{ $item->title }}
                        </h1>
                        <div class="purchase-item__price">¥ {{ number_format($item->price) }}</div>
                    </div>
                </div>

                <div class="purchase-rule"></div>

                {{-- 支払い方法 --}}
                <div class="purchase-payment">
                    <h2>支払い方法</h2>

                    @php
                        $selectedPaymentMethod = old('payment_method', request('payment_method', ''));
                        $selectedPaymentLabel = $payments[$selectedPaymentMethod] ?? '選択してください';
                    @endphp

                    <div class="purchase-payment-custom">
                        <button type="button" class="purchase-payment-custom__trigger" id="js-payment-trigger">
                            <span id="js-payment-trigger-label">{{ $selectedPaymentLabel }}</span>
                            <span class="purchase-payment-custom__arrow">▾</span>
                        </button>

                        <ul class="purchase-payment-custom__menu is-hidden" id="js-payment-menu">
                            @foreach ($payments as $key => $label)
                                <li class="purchase-payment-custom__option {{ $selectedPaymentMethod === $key ? 'is-selected' : '' }}"
                                    data-value="{{ $key }}" data-label="{{ $label }}">
                                    {{ $label }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @error('payment_method')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="purchase-rule"></div>

                {{-- 配送先 --}}
                <section class="purchase-section">
                    <div class="purchase-section__head purchase-section__head--between">
                        <h1 class="purchase-section__title">配送先</h1>
                        <a href="{{ route('purchase.address.edit', $item) }}" class="purchase-link">
                            変更する
                        </a>
                    </div>

                    @error('shipping')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror

                    <div class="purchase-section__body">
                        <p>〒 {{ $shipping['postal_code'] }}</p>
                        <p>{{ $shipping['address_line1'] }}</p>

                        @if ($shipping['address_line2'] !== '')
                            <p>{{ $shipping['address_line2'] }}</p>
                        @endif
                    </div>
                </section>

                <div class="purchase-rule"></div>
            </div>

            {{-- 右カラム（まとめ） --}}
            <aside class="purchase-right">
                <div class="purchase-summary">
                    <div class="purchase-summary__row">
                        <div class="purchase-summary__label">商品代金</div>
                        <div class="purchase-summary__value">¥ {{ number_format($item->price) }}</div>
                    </div>

                    <div class="purchase-summary__row">
                        <div class="purchase-summary__label">支払い方法</div>
                        <div class="purchase-summary__value" id="js-payment-label">
                            {{ $payments[old('payment_method', request('payment_method'))] ?? '未選択' }}
                        </div>
                    </div>
                </div>

                {{-- 購入（POST） --}}
                <form method="POST" action="{{ route('purchase.store', $item) }}">
                    @csrf
                    <input type="hidden" name="payment_method" id="js-payment-hidden"
                        value="{{ old('payment_method', request('payment_method')) }}">
                    <button type="submit" class="purchase-btn">購入する</button>
                </form>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/purchase.js') }}" defer></script>
@endpush
