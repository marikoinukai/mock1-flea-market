<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;

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

Route::get('/', [ItemController::class, 'index']);

Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {

    Route::post('/item/{item}/like', [LikeController::class, 'store'])
        ->name('items.like');

    Route::delete('/item/{item}/like', [LikeController::class, 'destroy'])
        ->name('items.unlike');
});

// TODO(提出前に削除): Fortify導入後はこの仮ログインを削除する
if (app()->environment('local')) {
    Route::get('/login', function () {
        Auth::loginUsingId(1); // id=1 のユーザーで仮ログイン
        return back();
    })->name('login');
}
