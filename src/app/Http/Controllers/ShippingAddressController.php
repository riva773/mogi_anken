<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemShippingOverride;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ShippingAddressRequest;

class shippingAddressController extends Controller
{
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $shipping = $item->effectiveShippingAddressFor($user);
        return view('purchase.address_edit', compact('item', 'user', 'shipping'));
    }

    public function update(ShippingAddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $item = Item::findOrFail($item_id);

        ItemShippingOverride::updateOrCreate(
            [
                'item_id' => $item->id,
                'user_id' => $user->id,
            ],
            [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building') ?? null,
            ]
        );

        return redirect()->route('orders.create', $item_id);
    }
}
