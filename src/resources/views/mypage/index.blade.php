@extends('layouts.app')
@section('body_class', 'wide')
@section('content')
    <div class="mypage">
        <div class="mypage-header">

            <div class="mypage-avatar">
                @if ($user->icon_path)
                    <img src="{{ asset('storage/' . $user->icon_path) }}" alt="icon" class="mypage-avatar__img">
                @else
                    <div class="mypage-avatar__placeholder"></div>
                @endif
            </div>

            <div class="mypage-name">
                {{ $user->name }}
            </div>

            <a href="{{ route('profile.edit') }}" class="mypage-editBtn">
                プロフィールを編集
            </a>
        </div>

        <div class="mypage-tabs">
            <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="mypage-tab {{ $tab === 'sell' ? 'is-active' : '' }}">
                出品した商品
            </a>

            <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="mypage-tab {{ $tab === 'buy' ? 'is-active' : '' }}">
                購入した商品
            </a>
        </div>

        @php
            $items = $tab === 'buy' ? $buyItems : $sellItems;
        @endphp

        @if ($items->isEmpty())
            <p>表示する商品がありません。</p>
        @else
            <div class="items-grid">
                @foreach ($items as $item)
                    <a href="{{ route('items.show', $item) }}" class="item-card">
                        <div class="item-card__box">
                            <div class="item-card__media">
                                @if ($item->is_sold)
                                    <div class="item-card__sold">Sold</div>
                                @endif

                                @php
                                    $path = $item->image->image_path ?? null;
                                    $src = $path
                                        ? (str_starts_with($path, 'http')
                                            ? $path
                                            : asset('storage/' . $path))
                                        : null;
                                @endphp

                                @if ($src)
                                    <img src="{{ $src }}" class="item-card__img" alt="商品画像">
                                @else
                                    <div class="item-card__noimg">No Image</div>
                                @endif
                            </div>

                            <div class="item-card__body">
                                <div class="item-card__title">{{ $item->title }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
