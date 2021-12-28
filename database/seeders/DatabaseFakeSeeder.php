<?php

namespace Database\Seeders;

use App\Helpers\RolesType;
use App\Models\Meal;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(30)
            ->create()
            ->each(function ($user) {
                $role = $user->type === 'admin' ? RolesType::ADMIN_RESTAURANT : RolesType::LOCAL;
                $user->assignRole($role);

               if ($role === RolesType::ADMIN_RESTAURANT) {
                    $restaurant = $user
                        ->restaurant()
                        ->save(Restaurant::factory()->make());

                    $restaurant->meals()->saveMany(Meal::factory(10)->make()->unique('name'));
               }
            });
    }
}
