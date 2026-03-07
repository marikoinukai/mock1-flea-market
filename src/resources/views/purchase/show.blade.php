@extends('layouts.app')

@section('content')
    <div class="purchase-page">
        <div class="purchase-wrap">

            {{-- 左カラム --}}
            <div class="purchase-left">
                {{-- 商品 --}}
                <div class="purchase-item">
                    <div class="purchase-item__img">
                        @php
                            // item詳細と同じく、storage/ と http の両対応にしておくのが安全
                            $path = $item->image->image_path ?? null;
                            $src = $path ? (str_starts_with($path, 'http') ? $path : asset('storage/' . $path)) : null;
                        @endphp

                        @if ($src)
                            <img src="{{ $src }}" alt="商品画像">
                        @else
                            {{-- 画像が無い時はグレー枠だけ見せる（CSSで整える） --}}
                        @endif
                    </div>

                    <div class="purchase-item__meta">
                        <div class="purchase-item__title">{{ $item->title }}</div>
                        <div class="purchase-item__price">¥{{ number_format($item->price) }}</div>
                    </div>
                </div>

                <div class="purchase-rule"></div>

                {{-- 支払い方法 --}}
                <div class="purchase-payment">
                    <h2>支払い方法</h2>

                    {{-- 更新ボタン・GETフォームはやめる --}}
                    <select id="js-payment-select" class="purchase-payment-select">
                        <option value="">選択してください</option>
                        @foreach ($payments as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('payment_method', request('payment_method')) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>
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
                        <div class="purchase-summary__value">¥{{ number_format($item->price) }}</div>
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
