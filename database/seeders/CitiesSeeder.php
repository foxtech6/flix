<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::insert([
            ['name' => 'Berlin', 'country' => 'Germany'],
            ['name' => 'Paris', 'country' => 'France'],
            ['name' => 'Rome', 'country' => 'Italy'],
            ['name' => 'Madrid', 'country' => 'Spain'],
            ['name' => 'Lisbon', 'country' => 'Portugal'],
        ]);
    }
}
