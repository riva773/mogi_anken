<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $user = Auth::user();
        $validated = $request->validated();

        if ($validated['payment_method'] === 'カード支払い') {
            return redirect()->route('orders.card', ['item' => $item_id]);
        }

        try {
            DB::transaction(function () use ($request, $item_id, $user) {
                $item = Item::lockForUpdate()->findOrFail($item_id);

                if ($item->seller_id === $user->id) {
                    throw ValidationException::withMessages([
                        'order' => '自分が出品した商品は購入できません。'
                    ]);
                }

                if ($item->status === 'sold') {
                    throw ValidationException::withMessages([
                        'order' => 'すでに購入済みの商品は購入できません。'
                    ]);
                }

                $validated = $request->validated();

                $item->update([
                    'status'   => 'sold',
                    'buyer_id' => $user->id,
                ]);
            });

            return redirect('/')->with('status', '購入が完了しました。');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function card(Item $item)
    {
        $user = Auth::user();
        if ($item->seller_id === $user->id) {
            return back()->withErrors(['order' => '自分が出品した商品は購入できません。']);
        }
        if ($item->status === 'sold') {
            return back()->withErrors(['order' => 'すでに購入済みの商品は購入できません。']);
        }

        $intent = $user->createSetupIntent();

        return view('orders.card', [
            'item' => $item,
            'clientSecret' => $intent->client_secret,
            'stripeKey' => config('cashier.key'),
        ]);
    }
}
