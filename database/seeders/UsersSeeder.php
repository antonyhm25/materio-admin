<?php

namespace Database\Seeders;

use App\Helpers\RolesType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@domain.com',
            'password' => '$2y$10$yAE1YXJjlSOfb6fZz6RACO4WsuKGMVUc9Ow.am.kEtc/tfrZ1gNji', //secret
            'type' => 'ADMIN'
        ]);

        $superAdmin->assignRole(RolesType::SUPER_ADMIN);
    }
}
