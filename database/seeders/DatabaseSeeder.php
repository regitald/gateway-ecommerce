<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethodModel;
use App\Models\ShippingMethodModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('Database\Seeders\PaymentMethodSeeder');
        $this->call('Database\Seeders\ShippingMethodSeeder');
    }
}
