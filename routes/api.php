<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ReceiptController;
use App\Http\Controllers\Api\UserPlayerIdController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('password/resetlink',  [AuthController::class, 'sendResetLinkEmail']);
Route::post('password/reset',  [AuthController::class, 'resetPassword']);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('password/change', [AuthController::class, 'changePassword']);
});

// Companies Route
Route::get('get-company', [ReceiptController::class, 'companyIndex']);

// receipt Routes
Route::get('get-receipt', [ReceiptController::class, 'index']);
Route::post('receipt-store', [ReceiptController::class, 'store']);
Route::post('receipt-show', [ReceiptController::class, 'show']);

// User Player-Id Route
Route::get('get-player-id', [UserPlayerIdController::class, 'index']);
Route::post('store-player-id', [UserPlayerIdController::class, 'store']);