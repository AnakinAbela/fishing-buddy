<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FishingSpotController;
use App\Http\Controllers\CatchLogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('catches.index')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('spots', FishingSpotController::class);
    Route::resource('catches', CatchLogController::class);
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('likes/{catch}', [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::post('follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');
});
