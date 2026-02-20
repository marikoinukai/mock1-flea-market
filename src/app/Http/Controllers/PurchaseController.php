<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
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
        $request->validate([
            'payment_method' => ['required', 'in:convenience,card'],
        ]);

        $user = Auth::user();

        $shipping = session('purchase.shipping.' . $item->id);

        Order::create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,

            'shipping_postal_code' =>
            $shipping['postal_code'] ?? $user->postal_code,

            'shipping_address_line1' =>
            $shipping['address_line1'] ?? $user->address_line1,

            'shipping_address_line2' =>
            $shipping['address_line2'] ?? $user->address_line2,
        ]);

        session()->forget('purchase.shipping.' . $item->id);

        return redirect()->route('purchase.show', $item)
            ->with('success', '購入処理（仮）が完了しました');
    }
}
