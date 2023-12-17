<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PropertyController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/reg_user', [LoginController::class, 'registerUser']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

Route::prefix("properties")->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/{id}', [PropertyController::class, 'singleProperty']);
});

Route::prefix("saved-items")->group(function () {
    Route::post('/', [ItemsController::class, 'store']);
    Route::get('/{id}', [ItemsController::class, 'index']);
});
