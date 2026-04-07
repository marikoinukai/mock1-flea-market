@extends('layouts.app')
@section('body_class', 'wide')
@section('content')
    <div class="items-page">

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

        <div class="items-container">
            {{-- 件数ゼロ --}}
            @if ($items->isEmpty())
                @if (!($tab === 'mylist' && !auth()->check()))
                    <p class="items-empty">表示する商品がありません</p>
                @endif
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
                                    @php
                                        $path = $item->image->image_path ?? null;
                                        $src = $path
                                            ? (str_starts_with($path, 'http')
                                                ? $path
                                                : asset('storage/' . $path))
                                            : null;
                                    @endphp

                                    @if ($src)
                                        <img src="{{ $src }}" alt="商品画像" class="item-card__img">
                                    @else
                                        <div class="item-card__noimg">No Image</div>
                                    @endif

                                </div>

                                {{-- 商品名 --}}
                                <div class="item-card__body">
                                    <div class="item-card__title">
                                        {{ $item->title }}
                                    </div>
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
