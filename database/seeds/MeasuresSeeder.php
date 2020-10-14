<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('measures')->insert([
            [
                'name' => 'CM2',
            ],
            [
                'name' => 'CM3',
            ],
            [
                'name' => 'Unidades',
            ],
            [
                'name' => 'Rollo',
            ],
            [
                'name' => 'Kg',
            ],
            [
                'name' => 'M2',
            ],
        ]);
    }
}
