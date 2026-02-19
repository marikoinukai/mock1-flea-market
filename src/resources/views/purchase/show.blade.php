<h1>購入画面</h1>

<form method="GET" action="{{ route('purchase.show', $item) }}" class="purchase-page">
    {{-- ↑ まずはGETでOK：選択した支払い方法をURLに載せて表示反映させる方式 --}}
    {{-- まだ購入確定しないので GET が一番わかりやすい --}}

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

            <select name="payment_method" class="purchase-payment-select">
                <option value="">選択してください</option>
                @foreach ($payments as $key => $label)
                    <option value="{{ $key }}" {{ request('payment_method') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit">更新</button>
        </div>

        <hr>

        {{-- 配送先（今はダミー表示。User住所カラムを作ったら差し替え） --}}
        <div class="purchase-shipping">
            <h2>配送先</h2>
            <p>〒 {{ $user->postal_code }}</p>
            <p>
                {{ $user->address_line1 }}
                {{ $user->address_line2 }}
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

        <button class="purchase-submit" type="button" disabled>
            購入する（後で実装）
        </button>
    </div>

</form>
