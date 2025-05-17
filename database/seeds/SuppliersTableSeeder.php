<?php

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Proveedor Textil S.A.',
                'ruc' => '20123456789',
                'address' => 'Av. Industrias 123, Lima',
                'email' => 'textil@example.com',
                'first_phone' => '999888777',
                'second_phone' => '987654321',
                'third_phone' => null,
                'type' => 'Materiales',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Servicios Creativos SAC',
                'ruc' => '20987654321',
                'address' => 'Jr. Servicios 456, Lima',
                'email' => 'servicios@example.com',
                'first_phone' => '922334455',
                'second_phone' => null,
                'third_phone' => null,
                'type' => 'Servicios',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
