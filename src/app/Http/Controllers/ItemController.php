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
        $q = $request->query('q');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $items = collect();
            } else {
                $items = Item::query()->whereHas('likedBy', function ($builder) {
                    $builder->whereKey(Auth::id());
                })
                    ->when($q !== null && $q !== '', function ($query) use ($q) {
                        $query->where('name', 'LIKE', '%' . $q . '%');
                    })
                    ->latest()
                    ->get();
            }
        } else {
            $query = Item::query();
            if (Auth::check()) {
                $query->where('seller_id', '!=', Auth::id());
            }

            $query->when($q !== null && $q !== '', function ($sub) use ($q) {
                $sub->where('name', 'LIKE', '%' . $q . '%');
            });

            $items = $query->latest()->get();
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

        $item->save();

        return redirect()->route('items.show', $item->id)
            ->with('status', '出品が完了しました');
    }
}
