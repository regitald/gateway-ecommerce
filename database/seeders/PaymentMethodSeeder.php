<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('paymentmethod')->insert([
            'payment_method_name' => 'Manual Transfer',
            'payment_method_currency'  => 'IDR'
        ],);
    }
}
