<?php

namespace Database\Seeders;

use App\Helpers\PermissionsType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CatalogPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Permissions 
         */
        Permission::create([
            'name' => PermissionsType::PERMISSION_VIEW,
            'display' => trans('app.permissions.permissions-view'),
            'module' => trans('app.modules.permissions'),
        ]);

        /**
         * Roles 
         */
        Permission::create([
            'name' => PermissionsType::ROLES_VIEW,
            'display' => trans('app.permissions.roles-view'),
            'module' => trans('app.modules.roles'),
        ]);

        Permission::create([
            'name' => PermissionsType::ROLES_CREATE_UPDATE,
            'display' => trans('app.permissions.roles-create-update'),
            'module' => trans('app.modules.roles'),
        ]);

        Permission::create([
            'name' => PermissionsType::ROLES_DELETE,
            'display' => trans('app.permissions.roles-delete'),
            'module' => trans('app.modules.roles'),
        ]);

        /**
         * Users 
         */
        Permission::create([
            'name' => PermissionsType::USERS_VIEW,
            'display' => trans('app.permissions.users-view'),
            'module' => trans('app.modules.users'),
        ]);

        Permission::create([
            'name' => PermissionsType::USERS_CREATE_UPDATE,
            'display' => trans('app.permissions.users-create-update'),
            'module' => trans('app.modules.users'),
        ]);

        Permission::create([
            'name' => PermissionsType::USERS_DELETE,
            'display' => trans('app.permissions.users-delete'),
            'module' => trans('app.modules.users'),
        ]);

        /**
         * Account
         */
        Permission::create([
            'name' => PermissionsType::ACCOUNT_VIEW,
            'display' => trans('app.permissions.accounts-view'),
            'module' => trans('app.modules.accounts'),
        ]);

        Permission::create([
            'name' => PermissionsType::ACCOUNT_UPDATE,
            'display' => trans('app.permissions.accounts-update'),
            'module' => trans('app.modules.accounts'),
        ]);

        /**
         * Restaurants 
         */
        Permission::create([
            'name' => PermissionsType::RESTAURANTS_VIEW,
            'display' => trans('app.permissions.restaurants-view'),
            'module' => trans('app.modules.restaurants'),
        ]);

        Permission::create([
            'name' => PermissionsType::RESTAURANTS_CREATE_UPDATE,
            'display' => trans('app.permissions.restaurants-create-update'),
            'module' => trans('app.modules.restaurants'),
        ]);

        /**
         * Meals 
         */
        Permission::create([
            'name' => PermissionsType::MEALS_VIEW,
            'display' => trans('app.permissions.meals-view'),
            'module' => trans('app.modules.meals'),
        ]);

        Permission::create([
            'name' => PermissionsType::MEALS_CREATE_UPDATE,
            'display' => trans('app.permissions.meals-create-update'),
            'module' => trans('app.modules.meals'),
        ]);

        Permission::create([
            'name' => PermissionsType::MEALS_DELETE,
            'display' => trans('app.permissions.meals-delete'),
            'module' => trans('app.modules.meals'),
        ]);

        /**
         * Meal Deals 
         */
        Permission::create([
            'name' => PermissionsType::MEAL_DEALS_VIEW,
            'display' => trans('app.permissions.meal-deals-view'),
            'module' => trans('app.modules.meal-deals'),
        ]);

        Permission::create([
            'name' => PermissionsType::MEAL_DEALS_CREATE_UPDATE,
            'display' => trans('app.permissions.meal-deals-create-update'),
            'module' => trans('app.modules.meal-deals'),
        ]);

        Permission::create([
            'name' => PermissionsType::MEAL_DEALS_DELETE,
            'display' => trans('app.permissions.meal-deals-delete'),
            'module' => trans('app.modules.meal-deals'),
        ]);
        
        Permission::create([
            'name' => PermissionsType::MEAL_DEALS_STATUS,
            'display' => trans('app.permissions.meal-deals-status'),
            'module' => trans('app.modules.meal-deals'),
        ]);
    }
}
