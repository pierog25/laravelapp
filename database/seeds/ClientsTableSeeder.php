<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            [
                'id_document_type' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'document_number' => '12345678',
                'phone' => '987654321',
                'email' => 'john@example.com',
                'address' => '123 Main Street',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_document_type' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'document_number' => '73633890',
                'phone' => '912345678',
                'email' => 'jane@example.com',
                'address' => '456 Elm Street',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
