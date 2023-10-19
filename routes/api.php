<?php

use App\Http\Controllers\Api\PortalController;
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

Route::get('antara', [PortalController::class, 'antara']);
Route::get('cnbc', [PortalController::class, 'cnbc']);
Route::get('cnn', [PortalController::class, 'cnn']);
Route::get('kompas', [PortalController::class, 'kompas']);
Route::get('tirto', [PortalController::class, 'tirto']);
Route::get('republika', [PortalController::class, 'republika']);
