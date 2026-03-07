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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2 ?? null,
        ];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('icons', 'public');
            $data['icon_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('mypage');
    }
}
