<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $tab = request('tab', 'sell');

        // 出品した商品
        $sellItems = Item::with(['image'])
            ->where('seller_id', $user->id)
            ->latest()
            ->get();

        // 購入した商品
        $buyItems = Item::with(['image'])
            ->whereHas('orders', function ($q) use ($user) {
                $q->where('buyer_id', $user->id);
            })
            ->latest()
            ->get();

        return view('mypage.index', compact('user', 'tab', 'sellItems', 'buyItems'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $data = [
            'name' => $validated['name'],
            'postal_code' => $validated['postal_code'],
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
        ];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('icons', 'public');
            $data['icon_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('mypage');
    }
}
