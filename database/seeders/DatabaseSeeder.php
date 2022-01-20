<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User,Seller};
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Seller::factory(3)->create();
    }
}
