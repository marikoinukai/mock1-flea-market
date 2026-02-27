<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function show()
    {
    $user = auth()->user();

    // まずは画面表示だけ作る（中身は後で増やせる）
    return view('mypage.index', compact('user'));
    }
    
    public function edit()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
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

        return redirect()->route('items.index')->with('success', 'プロフィールを更新しました');
    }
}
