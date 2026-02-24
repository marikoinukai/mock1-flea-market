<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend'); // 'recommend' or 'mylist'
        $keyword = $request->query('keyword');  // 検索ワード

        $userId = Auth::id(); // 未ログインなら null

        // 一覧：必要なリレーションは eager load（N+1回避）
        $query = Item::query()->with(['image', 'seller', 'categories']);

        // 自分の出品は一覧に出さない（ログイン時のみ）
        if ($userId) {
            $query->where('seller_id', '!=', $userId);
        }

        // 検索：商品名（title）の部分一致
        if (!empty($keyword)) {
            $query->where('title', 'like', "%{$keyword}%");
        }

        // マイリスト（いいねした商品）
        if ($tab === 'mylist') {
            // 要件：未ログインなら何も表示（空）
            if (!$userId) {
                $items = collect();
                return view('items.index', compact('items', 'tab', 'keyword'));
            }

            $query->whereHas('likes', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        // 新着順 + ページング（tab/keyword維持）
        $items = $query->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show(Item $item)
    {
        $item->load(['image', 'seller', 'categories', 'condition', 'comments.user', 'likes']);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        // TODO: 後でDBマスタに置き換え
        $categories = [
            1 => 'レディース',
            2 => 'メンズ',
            3 => '家電',
            // ...
        ];

        $conditions = [
            1 => '新品・未使用',
            2 => '未使用に近い',
            3 => '目立った傷や汚れなし',
            // ...
        ];

        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = auth()->user();

        // ======================
        // ① item作成
        // ======================
        $item = Item::create([
            'seller_id' => $user->id,
            'title' => $request->title,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'item_condition_id' => $request->condition_id,
        ]);

        // ======================
        // ② 画像保存（storage）
        // ======================
        $path = $request->file('image')->store('items', 'public');

        ItemImage::create([
            'item_id' => $item->id,
            'image_path' => $path,
        ]);

        // ======================
        // ③ カテゴリ複数保存
        // ======================
        $item->categories()->sync($request->category_ids);

        return redirect()
            ->route('items.index')
            ->with('success', '商品を出品しました');
    }
}
