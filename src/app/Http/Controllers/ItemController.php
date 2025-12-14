<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('page', 'recommend');
        $q   = trim((string) $request->query('q', ''));
        $base = Item::query()->select(['id', 'name', 'image', 'price', 'status', 'seller_id']);
        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $items = collect();
            } else {
                $items = $base
                    ->whereHas('likedBy', fn($b) => $b->whereKey(Auth::id()))
                    ->where('seller_id', '!=', Auth::id())
                    ->when($q !== '', fn($q2) => $q2->where('name', 'like', "%{$q}%"))
                    ->latest()
                    ->get();
            }
        } else {
            $items = $base
                ->when(Auth::check(), fn($q2) => $q2->where('seller_id', '!=', Auth::id()))
                ->when($q !== '', fn($q2) => $q2->where('name', 'like', "%{$q}%"))
                ->latest()
                ->get();
        }
        return view('items.index', compact('items', 'tab'));
    }

    public function show($item_id)
    {
        $item = Item::with('comments')->withCount('likes', 'comments')->findOrFail($item_id);
        $comments = $item->comments()->get();
        $likedByMe = auth()->check() ? $item->likes()->where('user_id', auth()->id())->exists() : false;

        return view('items.show', compact('item', 'comments', 'likedByMe'));
    }

    public function store(CommentRequest $request, Item $item)
    {
        $validated = $request->validated();
        $item->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);
        return back();
    }

    public function create()
    {
        return view('items.create');
    }

    public function storeItem(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $item = new Item();
        $item->name        = $validated['name'];
        $item->price       = $validated['price'];
        $item->description = $validated['description'];
        $item->condition   = $validated['condition'];
        $item->seller_id   = Auth::id();
        $item->status      = 'for_sale';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $item->image = '/storage/' . $path;
        }

        $item->categories = array_values(array_unique($validated['categories'] ?? []));

        $item->save();

        return redirect()->route('items.show', $item->id);
    }
}
