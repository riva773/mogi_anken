<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        return view('users.edit_address', compact('item'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->name = $validated['name'];
        $user->postal_code = preg_replace('/\D/', '', (string)$validated['postal_code']) ?: null;
        $user->address = $validated['address'];
        $user->building = $validated['building'] ?? null;
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

    public function updateProfile(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = \Illuminate\Support\Facades\Auth::user();

        $user->name = $validated['name'];

        $postalRaw = $validated['postal_code'] ?? null;
        $user->postal_code = ($postalRaw === null || $postalRaw === '')
            ? $user->postal_code
            : preg_replace('/\D/', '', (string)$postalRaw);

        $user->address  = array_key_exists('address', $validated) && $validated['address'] !== ''
            ? $validated['address']
            : $user->address;

        $user->building = array_key_exists('building', $validated) && $validated['building'] !== ''
            ? $validated['building']
            : $user->building;


        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect('mypage');
    }
}
