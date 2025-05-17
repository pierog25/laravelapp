<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                UsersTableSeeder::class,
                DocumentTypesTableSeeder::class,
                ClientsTableSeeder::class,
                ProductsTableSeeder::class,
                OrderTableSeeder::class,
                OrderDetailsTableSeeder::class,
                SuppliersTableSeeder::class,
                PreSaleReportsTableSeeder::class,
                PreSaleReportDetailsTableSeeder::class,
            ]
        );
    }
}
