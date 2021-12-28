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
        $superAdmin = new User([
            'first_name' => 'Super Admin',
            'full_name' => 'Super Admin',
            'email' => 'superadmin@craft.com',
            'password' => '$2y$10$IjBDmIhlmJhpsuXu0NlJUeJ0PRNEMIpcd2c1Dg1fmsl4F.FDF6b6m', //password
        ]);

        $superAdmin->type = 'system';
        $superAdmin->save();

        $superAdmin->assignRole(RolesType::SUPER_ADMIN);
    }
}
