<h1>商品詳細ページ</h1>

<p>商品名：{{ $item->title }}</p>
<p>価格：{{ $item->price }}</p>

{{-- いいね・コメント数エリア --}}
<div class="item-reactions">

    @auth
        @php
            $liked = $item->likes->contains('user_id', auth()->id());
        @endphp

        @if ($liked)
            <form method="POST" action="{{ route('items.unlike', $item) }}">
                @csrf
                @method('DELETE')
                <button type="submit">
                    ♡ {{ $item->likes->count() }}
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('items.like', $item) }}">
                @csrf
                <button type="submit">
                    ♡ {{ $item->likes->count() }}
                </button>
            </form>
        @endif
    @else
        <a href="/login">
            ♡ {{ $item->likes->count() }}
        </a>
    @endauth

    <div>
        💬 {{ $item->comments->count() }}
    </div>

</div>

<p>出品者名：{{ $item->seller->name }}</p>
<p>画像のパス：{{ optional($item->image)->image_path }}</p>

@if ($item->image)
    <img src="{{ asset('img/' . $item->image->image_path) }}" alt="商品画像" style="max-width: 240px;">
@endif

@if ($item->categories->isEmpty())
    <p>カテゴリ：なし</p>
@else
    @foreach ($item->categories as $category)
        <p>カテゴリ名：{{ $category->name }}</p>
    @endforeach
@endif

<h2>いいね</h2>

<p>いいね数：{{ $item->likes->count() }}</p>

@auth
    @php
        $liked = $item->likes->contains('user_id', auth()->id());
    @endphp

    @if ($liked)
        <form method="POST" action="{{ route('items.unlike', $item) }}">
            @csrf
            @method('DELETE')
            <button type="submit">いいね解除</button>
        </form>
    @else
        <form method="POST" action="{{ route('items.like', $item) }}">
            @csrf
            <button type="submit">いいね</button>
        </form>
    @endif
@else
    <a href="/login">ログインしていいね</a>
    {{-- <a href="{{ route('login') }}">ログインしていいね</a> --}}
@endauth

<h2>コメント</h2>

@if ($item->comments->isEmpty())
    <p>コメントはまだありません</p>
@else
    @foreach ($item->comments as $comment)
        <div style="display:flex; gap:10px; margin-bottom:12px;">
            {{-- アイコン --}}
            <img src="{{ asset('img/default-user.png') }}" alt="user" width="32" height="32">
            <div>
                <p>{{ $comment->user->name }}</p>
                <p>{{ $comment->body }}</p>
            </div>
        </div>
    @endforeach
@endif
