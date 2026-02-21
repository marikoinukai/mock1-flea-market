<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
            'address_line1' => ['required', 'string', 'max:255'],
            // address_line2 があるなら任意
            'address_line2' => ['nullable', 'string', 'max:255'],
            // 画像は後で（今は一旦なし）
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2 ?? null,
        ]);

        return redirect()->route('items.index')->with('success', 'プロフィールを更新しました');
    }
}
