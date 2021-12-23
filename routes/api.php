<?php

use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/permissions', [PermissionsController::class, 'index']);

Route::post('/roles', [RolesController::class, 'store']);
Route::put('/roles/{role}', [RolesController::class, 'update']);
Route::delete('/roles/{role}', [RolesController::class, 'destroy']);
