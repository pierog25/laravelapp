<?php

use Illuminate\Database\Seeder;

class PreSaleReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pre_sale_reports')->insert([
            [
                'order_detail_id' => 1, // corresponde a 'Polo XL color azul'
                'unit_price' => 84.83,  // por ejemplo: (150.50 + 45.25) * 1.3 / 3
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
