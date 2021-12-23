<?php

namespace Database\Seeders;

use App\Helpers\PermissionsType;
use App\Helpers\RolesType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::findByName(RolesType::SUPER_ADMIN,);
        $admin = Role::findByName(RolesType::ADMIN);
        $auth = Role::findByName(RolesType::AUTH);

        $superAdmin->givePermissionTo(
            PermissionsType::PERMISSION_VIEW,

            PermissionsType::ROLES_VIEW,
            PermissionsType::ROLES_CREATE_UPDATE,
            PermissionsType::ROLES_DELETE,

            PermissionsType::USERS_VIEW,
            PermissionsType::USERS_CREATE_UPDATE,
            PermissionsType::USERS_DELETE,
            
            PermissionsType::RESTAURANTS_VIEW,
            PermissionsType::RESTAURANTS_CREATE_UPDATE,
            PermissionsType::RESTAURANTS_DELETE,
            
            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEALS_CREATE_UPDATE,
            PermissionsType::MEALS_DELETE,

            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEAL_DEALS_CREATE_UPDATE,
            PermissionsType::MEAL_DEALS_DELETE,
        );

        $admin->givePermissionTo(
            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEALS_CREATE_UPDATE,
            PermissionsType::MEALS_DELETE,

            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEAL_DEALS_CREATE_UPDATE,
            PermissionsType::MEAL_DEALS_DELETE,
        );

        $auth->givePermissionTo(
            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEAL_DEALS_STATUS,
        );
    }
}