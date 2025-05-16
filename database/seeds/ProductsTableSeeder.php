<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Camisa', 
                'abbreviation' => 'CA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Jeans',
                'abbreviation' => 'JE', 
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
