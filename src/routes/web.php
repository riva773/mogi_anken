<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;


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
