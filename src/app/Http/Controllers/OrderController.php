<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class OrderController extends Controller
{
    public function create(Item $item) {
        return view('orders.create', compact('item'));
    }
}
