@extends('layouts.app')

@section('content')
    <div class="items-page">
        <div class="items-container">

            {{-- タブ（検索keywordを保持） --}}
            <div class="items-tabs">

                {{-- おすすめ --}}
                <a href="{{ route('items.index', array_filter(['tab' => 'recommend', 'keyword' => $keyword])) }}"
                    class="items-tab {{ $tab === 'recommend' ? 'is-active' : '' }}">
                    おすすめ
                </a>

                {{-- マイリスト --}}
                <a href="{{ route('items.index', array_filter(['tab' => 'mylist', 'keyword' => $keyword])) }}"
                    class="items-tab {{ $tab === 'mylist' ? 'is-active' : '' }}">
                    マイリスト
                </a>

            </div>

            {{-- マイリスト：未ログインなら案内 --}}
            @if ($tab === 'mylist' && auth()->guest())
                <p class="items-muted">マイリストはログイン時のみ表示されます。</p>
            @else
                {{-- 件数ゼロ --}}
                @if ($items instanceof \Illuminate\Support\Collection || $items->isEmpty())
                    <p class="items-empty">表示する商品がありません。</p>
                @else
                    {{-- 一覧（4列グリッド） --}}
                    <div class="items-grid">
                        @foreach ($items as $item)
                            <a href="{{ route('items.show', $item) }}" class="item-card">

                                <div class="item-card__box">
                                    {{-- 画像枠 --}}
                                    <div class="item-card__media">
                                        {{-- Sold --}}
                                        @if ($item->is_sold)
                                            <div class="item-card__sold">Sold</div>
                                        @endif

                                        {{-- 画像 --}}
                                        @if ($item->image && $item->image->image_path)
                                            <img src="{{ asset('img/' . $item->image->image_path) }}" alt="商品画像"
                                                class="item-card__img">
                                        @else
                                            <div class="item-card__noimg">No Image</div>
                                        @endif
                                    </div>

                                    {{-- 商品名 --}}
                                    <div class="item-card__body">
                                        <div class="item-card__title">
                                            {{ $item->title }}
                                        </div>

                                        {{-- 出品者（アイコン＋名前） --}}
                                        <div class="item-card__seller">
                                            @if ($item->seller && $item->seller->icon_path)
                                                <img src="{{ asset('storage/' . $item->seller->icon_path) }}"
                                                    class="item-card__avatar" alt="seller">
                                            @else
                                                <span class="item-card__avatar item-card__avatar--placeholder"></span>
                                            @endif

                                            <span class="item-card__sellerName">
                                                {{ $item->seller->name ?? 'unknown' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        @endforeach
                    </div>

                    {{-- ページネーション --}}
                    <div class="items-pagination">
                        {{ $items->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
@endsection
