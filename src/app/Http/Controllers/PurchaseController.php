<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

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

    public function store(PurchaseRequest $request, Item $item)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // セッション配送先（無ければ空配列）
        $shipping = session("purchase.shipping.{$item->id}", []);

        // ✅ A案：配送先必須チェック（UIは変えない）
        $postal = $shipping['postal_code'] ?? $user->postal_code;
        $line1  = $shipping['address_line1'] ?? $user->address_line1;

        // 「郵便番号 or 住所」が無ければ購入させない（要件に合わせて厳しくするなら両方必須でもOK）
        if (empty($postal) || empty($line1)) {
            return back()->withErrors([
                'shipping' => '配送先が未設定です。住所変更から配送先を入力してください。',
            ])->withInput();
        }

        try {
            DB::transaction(function () use ($item, $user, $validated, $shipping) {

                // ★商品をロック
                $lockedItem = Item::where('id', $item->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 売り切れチェック
                if ($lockedItem->is_sold) {
                    throw new \RuntimeException('この商品は売り切れです');
                }

                // 注文作成
                Order::create([
                    'buyer_id' => $user->id,
                    'item_id'  => $lockedItem->id,
                    'payment_method' => $validated['payment_method'],

                    'shipping_postal_code'   => $shipping['postal_code'] ?? $user->postal_code,
                    'shipping_address_line1' => $shipping['address_line1'] ?? $user->address_line1,
                    'shipping_address_line2' => $shipping['address_line2'] ?? $user->address_line2,
                ]);

                // 売却済みに更新
                $lockedItem->update(['is_sold' => true]);
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

        $shipping = session("purchase.shipping.{$item->id}", [
            'postal_code' => $user->postal_code,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
        ]);

        return view('purchase.address', compact('item', 'user', 'shipping'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        $validated = $request->validated();

        session([
            "purchase.shipping.{$item->id}" => [
                'postal_code' => $validated['postal_code'],
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? '',
            ]
        ]);

        return redirect()
            ->route('purchase.show', $item)
            ->with('success', '配送先を更新しました');
    }
}
