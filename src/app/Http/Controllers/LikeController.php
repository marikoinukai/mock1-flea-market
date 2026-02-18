<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Item $item)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
        ]);

        return back();
    }

    public function destroy(Item $item)
    {
        Like::where('user_id', Auth::id())
            ->where('item_id', $item->id)
            ->delete();

        return back();
    }
}
