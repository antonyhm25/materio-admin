<?php

use App\Helpers\PermissionsType;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RolesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

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

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/permissions', [PermissionsController::class, 'index'])->middleware('permission:'. PermissionsType::PERMISSION_VIEW);

    Route::get('/roles', [RolesController::class, 'index'])->middleware('permission:'. PermissionsType::ROLES_VIEW);
    Route::get('/roles/{role}', [RolesController::class, 'show'])->middleware('permission:'. PermissionsType::ROLES_VIEW);
    Route::post('/roles', [RolesController::class, 'store'])->middleware('permission:'. PermissionsType::ROLES_CREATE_UPDATE);
    Route::put('/roles/{role}', [RolesController::class, 'update'])->middleware('permission:'. PermissionsType::ROLES_CREATE_UPDATE);
    Route::delete('/roles/{role}', [RolesController::class, 'destroy'])->middleware('permission:'. PermissionsType::ROLES_DELETE);

    Route::get('/users', [UsersController::class, 'index'])->middleware('permission:'. PermissionsType::USERS_VIEW);
    Route::get('/users/{user}', [UsersController::class, 'show'])->middleware('permission:'. PermissionsType::USERS_VIEW);
    Route::post('/users', [UsersController::class, 'store'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    Route::put('/users/{user}', [UsersController::class, 'update'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->middleware('permission:'. PermissionsType::USERS_DELETE);
});
