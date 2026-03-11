<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use App\Models\ItemCondition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            // 未ログインなら表示なし
            if (!$userId) {
                $items = collect();
                return view('items.index', compact('items', 'tab', 'keyword'));
            }

            $query->whereHas('likes', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        // 新着順
        $items = $query->orderByDesc('created_at')->get();
        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show(Item $item)
    {
        $item->load(['image', 'seller', 'categories', 'condition', 'comments.user', 'likes']);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::orderBy('id')->pluck('name', 'id');
        $conditions = ItemCondition::orderBy('id')->pluck('name', 'id');

        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // 先に画像保存
        $imageFile = $request->file('image');
        $path = $imageFile->store('items', 'public');

        try {

            DB::transaction(function () use ($user, $validated, $path) {

                // item作成
                $item = Item::create([
                    'seller_id' => $user->id,
                    'title' => $validated['title'],
                    'brand_name' => $validated['brand_name'] ?? null,
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'item_condition_id' => $validated['item_condition_id'],
                ]);

                // 画像DB保存
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);

                // カテゴリ複数保存
                $item->categories()->sync($validated['category_ids']);
            });
        } catch (\Throwable $e) {

            // DB失敗時は保存済み画像削除
            Storage::disk('public')->delete($path);

            throw $e;
        }

        return redirect()->route('items.index');
    }
}
