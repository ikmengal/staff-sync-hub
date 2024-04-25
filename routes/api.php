<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\StockController;

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
Route::get('get-company', [StockController::class, 'companyIndex']);

// Stock Routes
Route::get('get-stock', [StockController::class, 'index']);
Route::post('stock-store', [StockController::class, 'store']);
Route::post('stock-show', [StockController::class, 'show']);
