@extends('layouts.app')

@section('content')

    <div class="item-detail">

        {{-- 左：画像エリア --}}
        <div class="item-detail__left">
            <div class="item-detail__media">
                @php
                    $path = $item->image->image_path ?? null;
                    $src = $path ? (str_starts_with($path, 'http') ? $path : asset('storage/' . $path)) : null;
                @endphp

                @if ($src)
                    <img src="{{ $src }}" alt="商品画像" class="item-image">
                @else
                    <p class="item-image-empty">画像なし</p>
                @endif

                @if ($item->is_sold)
                    <div class="item-card__sold">Sold</div>
                @endif
            </div>
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
                    @php
                        $liked = auth()->check() && $item->likes->contains('user_id', auth()->id());
                    @endphp

                    @auth
                        @if ($liked)
                            <form method="POST" action="{{ route('items.unlike', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="reaction-like">
                                    <img src="{{ asset('img/heartlogo_red.png') }}" class="reaction-icon" alt="liked">
                                    <span>{{ $item->likes->count() }}</span>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('items.like', $item) }}">
                                @csrf
                                <button type="submit" class="reaction-like">
                                    <img src="{{ asset('img/heartlogo_default.png') }}" class="reaction-icon" alt="like">
                                    <span>{{ $item->likes->count() }}</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="/login" class="reaction-like">
                            <img src="{{ asset('img/heartlogo_default.png') }}" class="reaction-icon" alt="like">
                            <span>{{ $item->likes->count() }}</span>
                        </a>
                    @endauth

                    <div class="reaction-comment">
                        <img src="{{ asset('img/bubblelogo.png') }}" class="reaction-icon" alt="comment">
                        <span>{{ $item->comments->count() }}</span>
                    </div>
                </div>



                {{-- 購入ボタン --}}
                <div class="purchase-area">
                    @if ($item->is_sold)
                        <button class="purchase-button purchase-button--sold" disabled>
                            売り切れ
                        </button>
                    @else
                        @auth
                            <a href="{{ route('purchase.show', $item) }}" class="purchase-button">
                                購入手続きへ
                            </a>
                        @else
                            <a href="/login" class="purchase-button">
                                購入手続きへ
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

            {{-- 商品について --}}
            <div class="item-section item-section--description">
                <h1 class="item-section__title">商品説明</h1>
                <p class="item-description">
                    {{ $item->description ?? '（説明はありません）' }}
                </p>
            </div>

            <div class="item-section item-section--info">
                <h1 class="item-section__title">商品の情報</h1>

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

            {{-- コメント --}}
            <h1 class="comment-title">コメント({{ $item->comments->count() }})</h1>

            {{-- コメント一覧 --}}
            <div class="comment-list">
                @forelse ($item->comments as $comment)
                    <div class="comment-item">
                        @php $iconPath = optional($comment->user)->icon_path; @endphp

                        @if ($iconPath)
                            <img src="{{ asset('storage/' . $iconPath) }}" alt="user" class="comment-user-icon">
                        @else
                            <img src="{{ asset('img/default-user.png') }}" alt="user" class="comment-user-icon">
                        @endif

                        <div class="comment-body">
                            <p class="comment-user-name">{{ $comment->user->name }}</p>
                        </div>
                    </div>
                    <div class="comment-body">
                        <p class="comment-text">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="comment-empty">コメントはまだありません</p>
                @endforelse
            </div>

            {{-- 商品へのコメント（見本の文言） --}}
            <p class="comment-form-label">商品へのコメント</p>

            {{-- コメント投稿フォーム（ここは1回だけ） --}}
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
@endsection
