<?php

use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{user}', 'show')->name('show');
    });
    Route::controller(UserController::class)->prefix('user')->name('users.')->group(function () {
        Route::get('/', 'me')->name('me');
    });

    Route::controller(MessagesController::class)->prefix('messages')->name('users.')->group(function () {
        Route::get('/{user}', 'listMessages')->name('index');
        Route::post('/', 'store')->name('store');
    });
});
