@extends('layouts.app')

@section('content')
<div class="mypage">
  <div class="mypage-header">
    <div class="mypage-avatar"></div>

    <div class="mypage-name">
      {{ $user->name }}
    </div>

    <a href="{{ route('profile.edit') }}" class="mypage-editBtn">
      プロフィールを編集
    </a>
  </div>

  <div class="mypage-tabs">
    <a class="mypage-tab is-active" href="#">出品した商品</a>
    <a class="mypage-tab" href="#">購入した商品</a>
  </div>

  {{-- ここに「出品商品一覧」「購入商品一覧」を後で追加 --}}
</div>
@endsection