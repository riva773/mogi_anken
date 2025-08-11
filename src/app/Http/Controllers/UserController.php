<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class UserController extends Controller
{
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        return view('users/edit_address',compact('item'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        $user = Auth::user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('orders.create',['item_id' => $item_id]);
    }

    public function profile(){
        $user = Auth::user();
        return view('users.profile');
    }

    public function editProfile(){
        return view('users.edit_profile');
    }
    public function updateProfile(){
        return redirect('/');
    }
}
