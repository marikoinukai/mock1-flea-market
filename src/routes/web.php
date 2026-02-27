<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    // いいね
    Route::post('/item/{item}/like', [LikeController::class, 'store'])
        ->name('items.like');

    Route::delete('/item/{item}/like', [LikeController::class, 'destroy'])
        ->name('items.unlike');

    // コメント投稿（追加）
    Route::post('/item/{item}/comments', [CommentController::class, 'store'])
        ->name('items.comments.store');

    // マイページ
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'profile.completed'])->group(function () {

    // 購入
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])
        ->name('purchase.show');

    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])
        ->name('purchase.address.edit');

    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});
