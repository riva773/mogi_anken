<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        return view('orders.create', compact('item', 'user'));
    }

    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        if ($item->seller_id === $user->id) {
            return back();
        }

        if ($item->status === 'sold') {
            return back();
        }

        $item->update([
            'status' => 'sold',
            'buyer_id' => $user->id
        ]);
        return redirect('/');
    }
}
