<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Shipper, Sign, User, Seller};
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Sign::factory(1)->create();
        User::factory(10)->create();
        Seller::factory(3)->create();
        Shipper::factory(2)->create();
    }
}
