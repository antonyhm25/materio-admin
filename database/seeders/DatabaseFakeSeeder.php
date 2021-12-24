<?php

namespace Database\Seeders;

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
                $restaurant = $user
                    ->restaurant()
                    ->save(Restaurant::factory()->make());

                $restaurant->meals()->saveMany(Meal::factory(10)->make()->unique('name'));
            });
    }
}
