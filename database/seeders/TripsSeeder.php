<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $berlin = City::firstWhere(['name' => 'Berlin', 'country' => 'Germany']);
        $paris = City::firstWhere(['name' => 'Paris', 'country' => 'France']);
        $rome = City::firstWhere(['name' => 'Rome', 'country' => 'Italy']);
        $madrid = City::firstWhere(['name' => 'Madrid', 'country' => 'Spain']);
        $lisbon = City::firstWhere(['name' => 'Lisbon', 'country' => 'Portugal']);

        Trip::insert([
            [
                'code' => Str::random(6),
                'from_city_id' => $berlin->id,
                'to_city_id' => $paris->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $paris->id,
                'to_city_id' => $rome->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $rome->id,
                'to_city_id' => $madrid->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $madrid->id,
                'to_city_id' => $lisbon->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $lisbon->id,
                'to_city_id' => $berlin->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $paris->id,
                'to_city_id' => $madrid->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => Str::random(6),
                'from_city_id' => $rome->id,
                'to_city_id' => $lisbon->id,
                'places' => $places = rand(1, 30),
                'free_places' => rand(1, $places),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
