<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $shipping = session("purchase.shipping.{$item->id}", [
            'postal_code'   => $user->postal_code,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
        ]);

        return view('purchase.show', compact('item', 'user', 'payments', 'shipping'));
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'payment_method' => ['required', 'in:convenience,card'],
        ]);

        $user = Auth::user();
        $shipping = session("purchase.shipping.{$item->id}", []);

        try {

            DB::transaction(function () use ($item, $user, $request, $shipping) {

                // ★商品をロック
                $lockedItem = Item::where('id', $item->id)
                    ->lockForUpdate()
                    ->first();

                // 売り切れチェック
                if ($lockedItem->is_sold) {
                    throw new \RuntimeException('この商品は売り切れです');
                }

                // 注文作成
                Order::create([
                    'buyer_id' => $user->id,
                    'item_id'  => $lockedItem->id,
                    'payment_method' => $request->payment_method,

                    'shipping_postal_code' => $shipping['postal_code'] ?? $user->postal_code,
                    'shipping_address_line1' => $shipping['address_line1'] ?? $user->address_line1,
                    'shipping_address_line2' => $shipping['address_line2'] ?? $user->address_line2,
                ]);

                // 売却済みに更新
                $lockedItem->update([
                    'is_sold' => true
                ]);
            });
        } catch (\RuntimeException $e) {

            return back()->with('error', $e->getMessage());
        }

        session()->forget('purchase.shipping.' . $item->id);

        return redirect()->route('mypage', ['tab' => 'buy']);
    }

    public function editAddress(Item $item)
    {
        $user = auth()->user();

        // 住所変更ページ表示
        return view('purchase.address', compact('item', 'user'));
    }

    public function updateAddress(Request $request, Item $item)
    {
        $request->validate([
            'postal_code'    => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
        ]);

        // 購入フロー用に「セッション」に一時保存（ユーザー住所は更新しない想定）
        session([
            "purchase.shipping.{$item->id}" => [
                'postal_code'   => $request->postal_code,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
            ]
        ]);

        return redirect()
            ->route('purchase.show', $item)
            ->with('success', '配送先を更新しました');
    }
}
