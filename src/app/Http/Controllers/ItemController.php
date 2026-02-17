<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // 一覧に必要なもの（画像）をまとめて取得
        $items = Item::with('image', 'seller', 'categories')
            ->orderByDesc('created_at')
            ->get();

        // 画面はまだ作らず、JSONで確認する
        return response()->json($items);
    }

    public function show(Item $item)
    {
        $item->load(['image', 'seller', 'categories', 'comments.user', 'likes']);

        return view('items.show', compact('item'));
    }
}
