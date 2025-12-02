<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FishingSpotController;
use App\Http\Controllers\CatchLogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;

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

Route::resource('spots', FishingSpotController::class);
Route::resource('catches', CatchLogController::class);
Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('likes/{catch}', [LikeController::class, 'toggle'])->name('likes.toggle');
Route::post('follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');