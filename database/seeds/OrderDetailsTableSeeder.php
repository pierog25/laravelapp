<?php

use Illuminate\Database\Seeder;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_details')->insert([
            [
                'product_id' => 1,
                'order_id' => 1,
                'description' => 'Polo XL color azul',
                'quantity' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'order_id' => 1,
                'description' => 'Jean Desgarrado color negro',
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
