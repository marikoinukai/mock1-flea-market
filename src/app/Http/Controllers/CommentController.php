<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $validated = $request->validated();

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'body' => $request->input('body'),
        ]);

        return back();
    }
}
