<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
    	foreach (range(1,5) as $index) {
	        DB::table('shipping_method')->insert([
	            'shipping_name' => $faker->name,
                'cost' => $faker->unique()->numberBetween(8000, 20000),
                'distance' => $faker->unique()->numberBetween(3, 10)
	        ]);
	    }
    }
}
