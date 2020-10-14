<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SalesStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales_statuses')->insert([
            [
                'name' => 'En preparación',
            ],
            [
                'name' => 'Enviando',
            ],
            [
                'name'=> 'Entregado'
            ]
        ]);
    }
}
