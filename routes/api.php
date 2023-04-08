<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\NewsFeedController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPreferencesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|businessentertainmentgeneralhealthsciencesportstechnology
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/preferences', [UserPreferencesController::class, 'index'])->name('user-preferences.index');
    Route::post('/user/preferences', [UserPreferencesController::class, 'store'])->name('user-preferences.store');
    Route::put('/user/preferences/{id}', [UserPreferencesController::class, 'update'])->name('user.preferences.update');
    Route::delete('/user/preferences/{id}', [UserPreferencesController::class, 'destroy'])->name('user.preferences.destroy');

    Route::get('/user/details', [UserController::class, 'index'])->name('user-details.index');
    Route::put('/user/change-password', [UserController::class, 'changePassword'])->name('user.change.password');
    Route::put('/user/details', [UserController::class, 'update'])->name('user-details.update');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/available-categories', [CategoryController::class, 'getAvailableCategories'])->name('categories.available');

    Route::get('/newsfeed', [NewsFeedController::class, 'index'])->name('news-feed');
});
