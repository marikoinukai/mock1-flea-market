<h1>商品詳細ページ</h1>

<div class="item-detail">

    {{-- 左：画像エリア --}}
    <div class="item-detail__left">
        @if ($item->image)
            <img src="{{ asset('img/' . $item->image->image_path) }}" alt="商品画像" class="item-image">
        @else
            <p class="item-image-empty">画像なし</p>
        @endif
    </div>

    {{-- 右：情報エリア --}}
    <div class="item-detail__right">

        {{-- 上部（購入関連エリア） --}}
        <div class="item-side-top">

            <div class="item-header">
                <p class="item-title">{{ $item->title }}</p>

                <p class="item-brand">
                    {{ $item->brand_name ?? 'ブランド未設定' }}
                </p>

                <p class="item-price">¥{{ number_format($item->price) }}(税込)</p>
            </div>

            {{-- いいね・コメント数 --}}
            <div class="item-reactions">
                @auth
                    @php
                        $liked = $item->likes->contains('user_id', auth()->id());
                    @endphp

                    @if ($liked)
                        <form method="POST" action="{{ route('items.unlike', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="reaction-like">♡ {{ $item->likes->count() }}</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('items.like', $item) }}">
                            @csrf
                            <button type="submit" class="reaction-like">♡ {{ $item->likes->count() }}</button>
                        </form>
                    @endif
                @else
                    {{-- Fortify導入したら、ここは route('login') に戻す
    <a href="{{ route('login') }}" class="reaction-like" --}}

                    <a href="/login" class="reaction-like">♡ {{ $item->likes->count() }}</a>
                @endauth

                <div class="reaction-comment">💬 {{ $item->comments->count() }}</div>
            </div>

            <div class="purchase-area">
                @auth
                    <a href="/purchase/{{ $item->id }}" class="purchase-button">
                        購入手続きへ
                    </a>
                @else
                    <a href="/login" class="purchase-button">
                        購入手続きへ
                    </a>
                    <p class="purchase-login-note">購入するにはログインが必要です</p>
                @endauth
            </div>

        </div>

        {{-- 商品について --}}
        <div class="item-section item-section--description">
            <h2 class="item-section__title">商品説明</h2>
            <p class="item-description">
                {{ $item->description ?? '（説明はありません）' }}
            </p>
        </div>

        <div class="item-section item-section--info">
            <h2 class="item-section__title">商品の情報</h2>

            <div class="item-info">
                <div class="item-info__row">
                    <span class="item-info__label">カテゴリー</span>
                    <span class="item-info__value">
                        @if ($item->categories->isEmpty())
                            なし
                        @else
                            @foreach ($item->categories as $category)
                                <span class="item-category">{{ $category->name }}</span>
                            @endforeach
                        @endif
                    </span>
                </div>

                <div class="item-info__row">
                    <span class="item-info__label">商品の状態</span>
                    <span class="item-info__value">
                        {{ optional($item->condition)->name ?? '未設定' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- コメント投稿 --}}
        <h2 class="comment-title">コメント</h2>

        <div class="comment-area">
            @auth
                <form method="POST" action="{{ route('items.comments.store', $item) }}" class="comment-form">
                    @csrf

                    <textarea name="body" class="comment-input" placeholder="コメントを入力してください">{{ old('body') }}</textarea>

                    @error('body')
                        <p class="comment-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="comment-submit">コメントする</button>
                </form>
            @else
                <p class="comment-login-text">コメントするにはログインが必要です</p>
            @endauth
        </div>

    </div>

</div>

{{-- 下：コメント一覧 --}}
<div class="comment-list">
    @if ($item->comments->isEmpty())
        <p class="comment-empty">コメントはまだありません</p>
    @else
        @foreach ($item->comments as $comment)
            <div class="comment-item">
                <img src="{{ asset('img/default-user.png') }}" alt="user" class="comment-user-icon">

                <div class="comment-body">
                    <p class="comment-user-name">{{ $comment->user->name }}</p>
                    <p class="comment-text">{{ $comment->body }}</p>
                </div>
            </div>
        @endforeach
    @endif
</div>
