<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Module Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    "modules" => [
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'users' => 'Users',
        'restaurants' => 'Restaurants',
        'meals' => 'Meals',
        'meal-deals' => 'Meal Deals'
    ],

    'permissions' => [
        'permissions-view' => 'View Permission List',

        'roles-view' => 'View Role List',
        'roles-create-update' => 'Create and Update Roles',
        'roles-delete' => 'Delete Roles',

        'users-view' => 'View User List',
        'users-create-update' => 'Create and Update Users',
        'users-delete' => 'Delete Users',
        
        'restaurants-view' => 'View Restaurant List',
        'restaurants-create-update' => 'Create and Update Restaurants',
        'restaurants-delete' => 'Delete Restaurants',

        'meals-view' => 'View Meal List',
        'meals-create-update' => 'Create and Update Meals',
        'meals-delete' => 'Delete Meals',

        'meal-deals-view' => 'View Meal Deal List',
        'meal-deals-create-update' => 'Create and Update Meal Deals',
        'meal-deals-delete' => 'Delete Meal Deals',
        'meal-deals-status' => 'Change Status Meal Deals',
    ],

    'roles' => [
        'super-admin' => 'Super Administrator',
        'admin-restaurant' => 'Administrator Restaurant',
        'user-mobile' => 'User Mobile'
    ],

    'general' => [
        'error' => 'internal server error, try again later.',
        'auth' => 'The provided credentials are incorrect.',
        'logout' => 'Session closed successfully.',
    ],
];
