<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'vacfiltros@gmail.com',
                'password' => bcrypt('vacfiltros123')
            ],
            [
                'name' => 'Admin',
                'email' => 'ernesto.mazzei1@gmail.com',
                'password' => bcrypt('vacfiltros123')
            ]
        ]);
    }
}
