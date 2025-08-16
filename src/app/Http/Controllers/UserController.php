<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        return view('users.edit_address', compact('item'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        $user = Auth::user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('orders.create', ['item_id' => $item_id]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $items = Item::where('seller_id', $user->id)->get();
        $tab = $request->query('page', 'sell');
        if (!in_array($tab, ['sell', 'buy'], true)) {
            $tab = 'sell';
        }

        if ($tab === 'sell') {
            $items = $user->items()->latest('id')->get();
            return view('users.mypage', compact('user', 'tab', 'items'));
        } else {
            $purchases = $user->purchasedItems()->latest('id')->get();
            return view('users.mypage', compact('user', 'tab', 'purchases'));
        }

        return view('users.profile', compact('user', 'items'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('users.edit_profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $name = $request->input('name');
        if (empty($name)) {
            $user->name = $user->name;
        } else {
            $user->name = $name;
        }
        $user->postal_code = preg_replace('/\D/', '', (string)$request->input('postal_code')) ?: null;
        $user->address    = $request->input('address');
        $user->building   = $request->input('building');
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();


        return redirect('mypage');
    }
}
