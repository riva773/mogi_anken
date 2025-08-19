<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class OrderController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shipping = $item->effectiveShippingAddressFor($user);

        return view('orders.create', compact('item', 'user', 'shipping'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();

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
