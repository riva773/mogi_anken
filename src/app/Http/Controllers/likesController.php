<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class likesController extends Controller
{
    public function like($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $alreadyLiked = $item->likes()->where('user_id', $user->id)->exists();
        if (!$alreadyLiked) {
            $item->likes()->create([
                'user_id' => $user->id
            ]);
        }
        return redirect()->back();
    }

    public function unlike($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        }
        return redirect()->back();
    }
}
