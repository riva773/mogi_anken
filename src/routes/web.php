<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikesController;
use GuzzleHttp\Middleware;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::middleware('auth')->group(function () {
    Route::get('/sell',  [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'storeItem'])->name('items.store');
});

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');
Route::post('/item/{item}/comments', [ItemController::class, 'store'])->name('item.comments.store')->middleware('auth');
Route::get('purchase/{item_id}', [OrderController::class, 'create'])
    ->middleware('auth')
    ->name('orders.create');
Route::get('purchase/address/{item_id}', [UserController::class, 'editAddress'])
    ->middleware('auth')
    ->name('address.edit');
Route::post('purchase/address/{item_id}', [UserController::class, 'updateAddress'])
    ->middleware('auth')
    ->name('address.update');

Route::middleware('auth')->group(function () {
    Route::get('mypage', [UserController::class, 'profile'])->name('mypage');
    Route::get('mypage/profile', [UserController::class, 'editProfile'])->name('mypage.profile');
    Route::put('mypage/profile', [UserController::class, 'updateProfile'])->name('mypage.profile.update');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('mypage.profile')
        ->with('first_setup', true);
})->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended('/');
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/purchase/{item_id}', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');

Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/like', [LikesController::class, 'like'])->name('items.like');
    Route::delete('/item/{item_id}/like', [LikesController::class, 'unlike'])->name('items.unlike');
});
