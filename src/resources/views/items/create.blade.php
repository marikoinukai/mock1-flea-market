@extends('layouts.app')

@section('content')
    <div class="container sell-page">

        <h1 class="sell-title">商品の出品</h1>

        {{-- 全体エラー --}}
        @if ($errors->any())
            <div class="ui-alert ui-alert--danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
            @csrf

            {{-- 商品画像 --}}
            <div class="form-group">
                <label class="form-label">
                    商品画像 <span class="required">*</span>
                </label>
                <input class="ui-input" type="file" name="image" accept=".png,.jpeg">
                @error('image')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
                <p class="form-help">.png / .jpeg のみ（要件）</p>
            </div>

            {{-- カテゴリー --}}
            <div class="form-group">
                <label class="form-label">
                    カテゴリー <span class="required">*</span>
                </label>

                <div class="check-grid">
                    @foreach ($categories as $id => $name)
                        <label class="check-item">
                            <input type="checkbox" name="category_ids[]" value="{{ $id }}"
                                {{ in_array($id, old('category_ids', [])) ? 'checked' : '' }}>
                            <span>{{ $name }}</span>
                        </label>
                    @endforeach
                </div>

                @error('category_ids')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
                @error('category_ids.*')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品の状態 --}}
            <div class="form-group">
                <label class="form-label">
                    商品の状態 <span class="required">*</span>
                </label>
                <select class="ui-input" name="item_condition_id">
                    <option value="">選択してください</option>
                    @foreach ($conditions as $id => $name)
                        <option value="{{ $id }}"
                            {{ (string) old('item_condition_id') === (string) $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('item_condition_id')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品名 --}}
            <div class="form-group">
                <label class="form-label">
                    商品名 <span class="required">*</span>
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
                    商品説明 <span class="required">*</span>
                </label>
                <textarea class="ui-input" name="description" rows="6" maxlength="255">{{ old('description') }}</textarea>
                @error('description')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
                <p class="form-help">最大255文字（要件）</p>
            </div>

            {{-- 価格 --}}
            <div class="form-group">
                <label class="form-label">
                    価格 <span class="required">*</span>
                </label>
                <input class="ui-input" type="number" name="price" value="{{ old('price') }}" min="0"
                    step="1">
                @error('price')
                    <p class="ui-error">{{ $message }}</p>
                @enderror
                <p class="form-help">0円以上（要件）</p>
            </div>

            {{-- ボタン --}}
            <div class="form-actions">
                <button class="ui-button ui-button--primary" type="submit">出品する</button>
                <a class="ui-button" href="{{ route('items.index') }}">戻る</a>
            </div>

        </form>
    </div>
@endsection
