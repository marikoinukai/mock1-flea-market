<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');          // null or 'mylist'
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
}
