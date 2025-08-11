<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);
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
    Route::put('mypage', [UserController::class, 'updateProfile'])->name('mypage.update');
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