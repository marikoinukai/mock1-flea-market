<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit(Item $item)
    {
        $user = auth()->user();

        // すでに session にあればそれを優先（未確定の変更を保持）
        $shipping = session("purchase.shipping.{$item->id}", [
            'postal_code'   => $user->postal_code,
            'address_line1' => $user->address_line1,
            'address_line2' => $user->address_line2,
        ]);

        return view('purchase.address', compact('item', 'shipping'));
    }

    public function update(AddressRequest $request, Item $item)
    {
        $validated = $request->validated();

        session()->put("purchase.shipping.{$item->id}", [
            'postal_code'   => $request->postal_code,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
        ]);

        // 購入画面に戻す
        return redirect()
            ->route('purchase.show', $item)
            ->with('success', '配送先を更新しました');
    }
}
