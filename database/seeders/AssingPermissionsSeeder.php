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
        $adminRestaurant = Role::findByName(RolesType::ADMIN_RESTAURANT);
        $userMobile = Role::findByName(RolesType::LOCAL);

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
            
            PermissionsType::MEALS_VIEW,

            PermissionsType::MEAL_DEALS_VIEW,
        );

        $adminRestaurant->givePermissionTo(
            PermissionsType::ACCOUNT_VIEW,
            PermissionsType::ACCOUNT_UPDATE,

            PermissionsType::MEALS_VIEW,
            PermissionsType::MEALS_CREATE_UPDATE,
            PermissionsType::MEALS_DELETE,

            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEAL_DEALS_CREATE_UPDATE,
            PermissionsType::MEAL_DEALS_DELETE,
        );

        $userMobile->givePermissionTo(
            PermissionsType::ACCOUNT_VIEW,
            PermissionsType::ACCOUNT_UPDATE,
            
            PermissionsType::MEAL_DEALS_VIEW,
            PermissionsType::MEAL_DEALS_STATUS,
        );
    }
}
