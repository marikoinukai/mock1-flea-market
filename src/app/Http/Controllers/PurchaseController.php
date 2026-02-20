<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        // 購入画面で必要な情報の読み込み
        $item->load(['image']);

        $payments = [
            'convenience' => 'コンビニ支払い',
            'card' => 'カード支払い',
        ];

        $user = Auth::user(); // 配送先表示用

        return view('purchase.show', compact('item', 'user', 'payments'));
    }

    public function store(Request $request,  Item $item)
    {
        dd($request->all(), $item->id);
    }
}
