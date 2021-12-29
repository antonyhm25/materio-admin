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

    Route::get('/permissions', [PermissionsController::class, 'index']);

    Route::get('/roles', [RolesController::class, 'index']);
    Route::get('/roles/{role}', [RolesController::class, 'show']);
    Route::get('/roles/{role}/permissions', [RolesController::class, 'permissions']);
    Route::post('/roles', [RolesController::class, 'store']);
    Route::put('/roles/{role}', [RolesController::class, 'update']);
    Route::delete('/roles/{role}', [RolesController::class, 'destroy']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/{user}', [UsersController::class, 'show']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::put('/users/{user}/password-reset', [UsersController::class, 'passwordReset']);
    Route::put('/users/{user}/password-change', [UsersController::class, 'passwordChange']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);
    Route::post('/users/delete-many', [UsersController::class, 'destroyMany']);

    Route::get('/users/{user}/local/meal-deals', [UserMobilesController::class, 'mealDeals']);
    Route::put('/users/{user}/local', [UserMobilesController::class, 'update']);
    
    Route::post('/restaurants', [RestaurantsController::class, 'store']);
    Route::put('/restaurants/{user}', [RestaurantsController::class, 'update']);
    Route::post('/restaurants/{restaurant}/photo', [RestaurantsController::class, 'uploadPhoto']);

    Route::get('/restaurants/{restaurant}/meals', [MealsController::class, 'index']);
    Route::get('/restaurants/{restaurant}/meals/{meal}', [MealsController::class, 'show']);
    Route::post('/restaurants/{restaurant}/meals', [MealsController::class, 'store']);
    Route::post('/restaurants/{restaurant}/meals/{meal}/photo', [MealsController::class, 'uploadPhoto']);
    Route::put('/restaurants/{restaurant}/meals/{meal}', [MealsController::class, 'update']);
    Route::delete('/restaurants/{restaurant}/meals/{meal}', [MealsController::class, 'destroy']);
    Route::post('/restaurants/{restaurant}/meals/destroy-many', [MealsController::class, 'destroyMany']);
    
    Route::get('/restaurants/{restaurant}/meal-deals', [MealDealsController::class, 'index']);
    Route::get('/restaurants/{restaurant}/meal-deals/{meal}', [MealDealsController::class, 'show']);
    Route::post('/restaurants/{restaurant}/meal-deals', [MealDealsController::class, 'store']);
    Route::put('/restaurants/{restaurant}/meal-deals/{meal}', [MealDealsController::class, 'update']);
    Route::delete('/restaurants/{restaurant}/meal-deals/{meal}', [MealDealsController::class, 'destroy']);

    Route::get('/meal-deals', [MealDealsController::class, 'mealDeals']);
    Route::put('/meal-deals/{meal}/reserved', [MealDealsController::class, 'reserved'])->middleware('permission:'. PermissionsType::MEAL_DEALS_STATUS);
});
