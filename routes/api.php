<?php

use App\Helpers\PermissionsType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MealsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\MealDealsController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RestaurantsController;
use App\Http\Controllers\Api\UserMobilesController;

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

Route::post('/auth/admin/login', [AuthController::class, 'loginAdmin']);
Route::post('/auth/local/login', [AuthController::class, 'loginLocal']);

Route::post('/auth/local/register', [UserMobilesController::class, 'store']);

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

    Route::put('/users/{user}/local', [UserMobilesController::class, 'update']);
    
    Route::post('/restaurants', [RestaurantsController::class, 'store'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    Route::put('/restaurants/{user}', [RestaurantsController::class, 'update'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);
    Route::post('/restaurants/{restaurant}/photo', [RestaurantsController::class, 'uploadPhoto'])->middleware('permission:'. PermissionsType::USERS_CREATE_UPDATE);

    Route::get('/meals', [MealsController::class, 'index'])->middleware('permission:'. PermissionsType::MEALS_VIEW);
    Route::get('/meals/{meal}', [MealsController::class, 'show'])->middleware('permission:'. PermissionsType::MEALS_VIEW);
    Route::post('/meals', [MealsController::class, 'store'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::post('/meals/{meal}/photo', [MealsController::class, 'uploadPhoto'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::put('/meals/{meal}', [MealsController::class, 'update'])->middleware('permission:'. PermissionsType::MEALS_CREATE_UPDATE);
    Route::delete('/meals/{meal}', [MealsController::class, 'destroy'])->middleware('permission:'. PermissionsType::MEALS_DELETE);
    
    Route::get('/meal-deals', [MealDealsController::class, 'index'])->middleware('permission:'. PermissionsType::MEAL_DEALS_VIEW);
    Route::get('/meal-deals/{meal}', [MealDealsController::class, 'show'])->middleware('permission:'. PermissionsType::MEAL_DEALS_VIEW);
    Route::post('/meal-deals', [MealDealsController::class, 'store'])->middleware('permission:'. PermissionsType::MEAL_DEALS_CREATE_UPDATE);
    Route::put('/meal-deals/{meal}', [MealDealsController::class, 'update'])->middleware('permission:'. PermissionsType::MEAL_DEALS_CREATE_UPDATE);
    Route::put('/meal-deals/{meal}/reserved', [MealDealsController::class, 'reserved'])->middleware('permission:'. PermissionsType::MEAL_DEALS_STATUS);
    Route::delete('/meal-deals/{meal}', [MealDealsController::class, 'destroy'])->middleware('permission:'. PermissionsType::MEAL_DEALS_DELETE);
});
