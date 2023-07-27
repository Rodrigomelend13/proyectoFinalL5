<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListaItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 1,
        	'lista_id' => 1
        ]);
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 1,
        	'lista_id' => 2
        ]);
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 2,
        	'lista_id' => 1
        ]);
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 2,
        	'lista_id' => 2
        ]);
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 3,
        	'lista_id' => 1
        ]);
        DB::table('item_list')->insertOrIgnore([
        	'item_id' => 4,
        	'lista_id' => 3
        ]);
        
    }
}