<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
                'client_id' => 1,
                'delivery_date' => now()->addDays(10),
                'delivery_location' => 'Taller Los Olivos',
                'order_status' => 'Por Cotizar',
                'issue_date' => now(),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
