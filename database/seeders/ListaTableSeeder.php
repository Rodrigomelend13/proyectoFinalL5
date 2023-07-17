<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ListaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list')->insert([
        	'name' => 'Primera lista',
        	'active' => true,
            'user_id' => 'd267f964-105e-11ed-861d-0242ac120002',
            'canceled' => false,
        	'created_at' => NOW(),
        	'updated_at' => NOW()
        ]);
        DB::table('list')->insert([
        	'name' => 'Segunda lista',
        	'active' => false,
            'user_id' => 'd267f964-105e-11ed-861d-0242ac120002',
            'canceled' => false,
        	'created_at' => NOW(),
        	'updated_at' => NOW()
        ]);
        DB::table('list')->insert([
        	'name' => 'Tercera lista',
        	'active' => false,
            'user_id' => 'd267f964-105e-11ed-861d-0242ac120002',
            'canceled' => false,
        	'created_at' => NOW(),
        	'updated_at' => NOW()
        ]);
        DB::table('list')->insert([
        	'name' => 'Cuarta lista',
        	'active' => false,
            'user_id' => 'd267f964-105e-11ed-861d-0242ac120002',
            'canceled' => true,
        	'created_at' => NOW(),
        	'updated_at' => NOW()
        ]);
    }
}