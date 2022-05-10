<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{AppVersion, Country, Shipper, Sign, Type, User, Seller};
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Artisan::call('generate:roles');
//        AppVersion::factory(1)->create();
//        Sign::factory(1)->create();
//        Type::factory(1)->create();
//        Country::factory(1)->create();
//        User::factory(10)->create();
        Seller::factory(3)->create();
//        Shipper::factory(2)->create();
    }
}
