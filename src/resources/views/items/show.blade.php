<h1>商品詳細ページ</h1>

<p>商品名：{{ $item->title }}</p>
<p>価格：{{ $item->price }}</p>
<p>出品者名：{{ $item->seller->name }}</p>
<p>画像のパス：{{ $item->image->image_path }}</p>

@if ($item->image)
    <img src="{{ asset('img/'.$item->image->image_path) }}" alt="item">
@endif

@foreach ($item->categories as $category)
    <p>カテゴリ名：{{ $category->name }}</p>
@endforeach

<h2>コメント</h2>

@if ($item->comments->isEmpty())
    <p>コメントはまだありません</p>
@else
    @foreach ($item->comments as $comment)
    <div style="display:flex; gap:10px; margin-bottom:12px;">
      {{-- アイコン --}}
      <img src="{{ asset('img/default-user.png') }}" alt="user" width="32" height="32">
    </div>
            <div>
                <p>{{ $comment->user->name }}</p>
                <p>{{ $comment->body }}</p>
            </div>

    @endforeach
@endif
