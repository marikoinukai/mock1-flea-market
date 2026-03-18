@extends('layouts.app')

@section('content')
    <div class="container sell-page">

        <h1 class="sell-title">商品の出品</h1>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
            @csrf

            {{-- 商品画像 --}}
            <div class="sell-image">
                <label class="sell-image__label">
                    商品画像
                </label>

                <label class="sell-image__drop">
                    <input class="sell-image__input" type="file" name="image" accept=".png,.jpeg">
                    <span class="sell-image__btn">画像を選択する</span>
                </label>

                @error('image')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <h2 class="sell-section__title">商品の詳細</h2>
            <div class="sell-section__rule"></div>

            {{-- カテゴリー --}}
            <div class="form-group">
                <label class="form-label">
                    カテゴリー
                </label>

                <div class="check-grid">
                    @foreach ($categories as $id => $name)
                        <label class="check-item">
                            <input class="check-input" type="checkbox" name="category_ids[]" value="{{ $id }}"
                                {{ in_array($id, old('category_ids', [])) ? 'checked' : '' }}>
                            <span class="check-chip">{{ $name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('category_ids')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品の状態 --}}
            <div class="form-group">
                <label class="form-label">
                    商品の状態
                </label>

                @php
                    $selectedConditionId = old('item_condition_id', '');
                    $selectedConditionLabel = $conditions[$selectedConditionId] ?? '選択してください';
                @endphp

                <div class="sell-condition-custom">
                    <button type="button" class="sell-condition-custom__trigger" id="js-condition-trigger">
                        <span id="js-condition-trigger-label">{{ $selectedConditionLabel }}</span>
                        <span class="sell-condition-custom__arrow">▾</span>
                    </button>

                    <ul class="sell-condition-custom__menu is-hidden" id="js-condition-menu">
                        @foreach ($conditions as $id => $name)
                            <li class="sell-condition-custom__option {{ (string) $selectedConditionId === (string) $id ? 'is-selected' : '' }}"
                                data-value="{{ $id }}" data-label="{{ $name }}">
                                {{ $name }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <input type="hidden" name="item_condition_id" id="js-condition-hidden" value="{{ $selectedConditionId }}">

                @error('item_condition_id')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            <h2 class="sell-section__title">商品名と説明</h2>
            <div class="sell-section__rule"></div>

            {{-- 商品名 --}}
            <div class="form-group">
                <label class="form-label">
                    商品名
                </label>
                <input class="ui-input" type="text" name="title" value="{{ old('title') }}" maxlength="255">
                @error('title')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ブランド名 --}}
            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input class="ui-input" type="text" name="brand_name" value="{{ old('brand_name') }}" maxlength="255">
                @error('brand_name')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品説明 --}}
            <div class="form-group">
                <label class="form-label">
                    商品の説明
                </label>
                <textarea class="ui-input" name="description" rows="6">{{ old('description') }}</textarea>
                @error('description')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 価格 --}}
            <div class="form-group">
                <label class="form-label">
                    販売価格
                </label>
                <div class="price-input">
                    <span class="price-input__yen">¥</span>

                    <input class="ui-input price-input__field" type="text" name="price" value="{{ old('price') }}"
                        inputmode="numeric">
                </div>
                @error('price')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ボタン --}}
            <div class="form-actions">
                <button class="sell-submit" type="submit">出品する</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/sell.js') }}" defer></script>
@endpush
