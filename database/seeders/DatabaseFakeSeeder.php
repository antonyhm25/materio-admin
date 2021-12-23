<?php

namespace Database\Seeders;

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
        User::factory(100)
            ->create()
            ->each(function ($item) {
                $item->restaurant()
                    ->save(Restaurant::factory()->make());
            });
    }
}
