@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- タブ（検索keywordを保持） --}}
        <div style="display:flex; gap:16px; align-items:center; margin-bottom:16px;">
            <a href="{{ route('items.index', array_filter(['keyword' => $keyword])) }}"
                style="padding:6px 10px; border-bottom:2px solid {{ $tab === 'mylist' ? 'transparent' : '#000' }};">
                おすすめ
            </a>

            <a href="{{ route('items.index', array_filter(['tab' => 'mylist', 'keyword' => $keyword])) }}"
                style="padding:6px 10px; border-bottom:2px solid {{ $tab === 'mylist' ? '#000' : 'transparent' }};">
                マイリスト
            </a>
        </div>

        {{-- マイリスト：未ログインなら「何も表示しない」＝空のままに近い表示 --}}
        @if ($tab === 'mylist' && auth()->guest())
            <p style="color:#666;">マイリストはログイン時のみ表示されます。</p>
        @else
            {{-- 件数ゼロ --}}
            @if ($items instanceof \Illuminate\Support\Collection || $items->isEmpty())
                <p>表示する商品がありません。</p>
            @else
                {{-- 一覧（4列グリッド） --}}
                <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:16px;">
                    @foreach ($items as $item)
                        <a href="{{ route('items.show', $item) }}"
                            style="display:block; text-decoration:none; color:inherit;">

                            <div style="border:1px solid #ddd; border-radius:8px; overflow:hidden; background:#fff;">
                                {{-- 画像枠 --}}
                                <div style="position:relative; aspect-ratio:1/1; background:#f5f5f5; overflow:hidden;">
                                    {{-- Sold --}}
                                    @if ($item->is_sold)
                                        <div
                                            style="position:absolute; top:8px; left:8px; background:#000; color:#fff; padding:2px 8px; font-size:12px; border-radius:4px;">
                                            Sold
                                        </div>
                                    @endif

                                    {{-- 画像 --}}
                                    @if ($item->image && $item->image->image_path)
                                        <img src="{{ asset('img/' . $item->image->image_path) }}" alt="商品画像"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div
                                            style="height:100%; display:flex; align-items:center; justify-content:center; color:#999;">
                                            No Image
                                        </div>
                                    @endif
                                </div>

                                {{-- 商品名 --}}
                                <div style="padding:10px;">
                                    <div style="font-weight:bold; font-size:14px; line-height:1.4;">
                                        {{ $item->title }}
                                    </div>
                                </div>
                                <div class="item-card__seller">
    @if ($item->seller && $item->seller->icon_path)
        <img
            src="{{ asset('storage/' . $item->seller->icon_path) }}"
            class="item-card__avatar"
            alt="seller"
        >
    @else
        <span class="item-card__avatar item-card__avatar--placeholder"></span>
    @endif

    <span class="item-card__sellerName">
        {{ $item->seller->name ?? 'unknown' }}
    </span>
</div>
                            </div>

                        </a>
                    @endforeach
                </div>

                {{-- ページネーション（tab/keyword維持は controller の withQueryString() が効く） --}}
                <div style="margin-top:16px;">
                    {{ $items->links() }}
                </div>
            @endif
        @endif

    </div>
@endsection
