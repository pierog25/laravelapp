<?php

use Illuminate\Database\Seeder;

class PreSaleReportDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pre_sale_report_details')->insert([
            [
                'pre_sale_report_id' => 1,
                'resource' => 'Tela algodón azul',
                'supplier_id' => 1,
                'cost' => 150.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pre_sale_report_id' => 1,
                'resource' => 'Hilo industrial',
                'supplier_id' => 2,
                'cost' => 45.25,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
