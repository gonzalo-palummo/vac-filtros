<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PurchasesStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchases_statuses')->insert([
            [
                'name' => 'Pendiente',
            ],
            [
                'name' => 'Pagado',
            ],
            [
                'name'=> 'Vencido'
            ]
        ]);
    }
}
