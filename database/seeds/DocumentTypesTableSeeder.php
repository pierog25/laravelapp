<?php

use Illuminate\Database\Seeder;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->insert([
            [
                'name' => 'DNI',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pasaporte',                 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'RUC',                 
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
