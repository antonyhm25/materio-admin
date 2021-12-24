<?php

use App\Helpers\PermissionsType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MealsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\UserConsumersController;

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

Route::post('/auth/login', [AuthController::class, 'loginAdmin']);

Route::post('/auth/local/register', [UserConsumersController::class, 'store']);

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
    
    Route::post('/local/users', [UserConsumersController::class, 'store'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    Route::put('/local/users/{user}', [UserConsumersController::class, 'update'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    
    Route::post('/restaurants/{restaurant}/photo', [UserConsumersController::class, 'uploadPhoto'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);

    Route::get('/meals', [MealsController::class, 'index'])->middleware('permission:'. PermissionsType::MEALS_VIEW);
    Route::get('/meals/{meal}', [MealsController::class, 'show'])->middleware('permission:'. PermissionsType::MEALS_VIEW);
    Route::post('/meals', [MealsController::class, 'store'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::post('/meals/{meal}/photo', [MealsController::class, 'uploadPhoto'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::put('/meals/{meal}', [MealsController::class, 'update'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::delete('/meals/{meal}', [MealsController::class, 'destroy'])->middleware('permission:'. PermissionsType::MEALS_DELETE);
});
