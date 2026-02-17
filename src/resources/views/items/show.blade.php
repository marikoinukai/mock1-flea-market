<h1>商品詳細ページ</h1>

<p>商品名：{{ $item->title }}</p>
<p>価格：{{ $item->price }}</p>
<p>出品者名：{{ $item->seller->name }}</p>
<p>画像のパス：{{ $item->image->image_path }}</p>
@foreach ($item->categories as $category)
    <p>カテゴリ名：{{ $category->name }}</p>
@endforeach
