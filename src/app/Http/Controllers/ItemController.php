<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    public function index()
    {
        $query = Item::query();
        if (Auth::check()) {
            $query->where('seller_id', '!=', Auth::id());
        }
        $items = $query->latest()->get();
        return view('items.index', compact('items'));
    }

    public function show($item_id)
    {
        $item =  Item::find($item_id);
        $comments = $item->comments;
        return view('items.show', compact('item', 'comments'));
    }

    public function store(Request $request, Item $item)
    {
        $item->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content')
        ]);
        return back();
    }

    public function create()
    {
        return view('items.create');
    }

    public function storeItem(Request $request)
    {
        $item = new Item();
        $item->name        = $request->input('name');
        $item->price       = $request->input('price');
        $item->description = $request->input('description');
        $item->condition   = $request->input('condition');
        $item->seller_id   = Auth::id();
        $item->status      = 'for_sale';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $item->image = '/storage/' . $path;
        }

        $item->save();

        return redirect()->route('items.show', $item->id)
            ->with('status', '出品が完了しました');
    }
}
