<?php

namespace Database\Seeders;

use App\Helpers\RolesType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => RolesType::SUPER_ADMIN,
            'display' => trans('app.roles.super-admin'),
            'locked' => 1,
        ]);
        
        Role::create([
            'name' => RolesType::ADMIN,
            'display' => trans('app.roles.admin'),
            'locked' => 1,
        ]);

        Role::create([
            'name' => RolesType::AUTH,
            'display' => trans('app.roles.auth'),
            'locked' => 1,
        ]);
    }
}
