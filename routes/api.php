<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserInfoController;
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

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::prefix('user')->group(function () {
    Route::post('upload-avatar', [UserInfoController::class, 'uploadAvatar']);
    Route::post('{id}', [UserInfoController::class, 'create']);
    Route::get('/get-avatar/{id}', [UserInfoController::class, 'getAvatar']);
    Route::get('{user_id}/show', [UserInfoController::class, 'show']);
    Route::get('me', [UserInfoController::class, 'me']);
    Route::put('{id}',[UserInfoController::class,'update']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
