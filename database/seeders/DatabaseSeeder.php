<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $created_at = date('Y-m-d H:i:s');

        DB::table('currencies')->insert([
            ['name' => 'US Dollar',     'ISO' => 'USD', 'surcharge' => 0,   'created_at' => $created_at, 'updated_at' => $created_at],
            ['name' => 'Japanese Yen',  'ISO' => 'JPY', 'surcharge' => 7.5, 'created_at' => $created_at, 'updated_at' => $created_at],
            ['name' => 'British Pound', 'ISO' => 'GBP', 'surcharge' => 5,   'created_at' => $created_at, 'updated_at' => $created_at],
            ['name' => 'EURO',          'ISO' => 'EUR', 'surcharge' => 5,   'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        DB::table('order_actions')->insert([
            ['name' => 'discount',  'parameter' => 'percentage',    'created_at' => $created_at, 'updated_at' => $created_at],
            ['name' => 'sendmail',  'parameter' => 'mail',          'created_at' => $created_at, 'updated_at' => $created_at],
        ]);

        DB::table('currencies_order_actions')->insert([
            ['currency' => 'EUR',   'action' => 1, 'parameter' => '2',                      'created_at' => $created_at, 'updated_at' => $created_at],
            ['currency' => 'GBP',   'action' => 2, 'parameter' => 'menutest@yopmail.com',   'created_at' => $created_at, 'updated_at' => $created_at]
        ]);

        DB::table('rates')->insert([
            ['from' => 'USD',   'to' => 'JPY', 'rate' => 107.17,    'created_at' => $created_at,    'updated_at' => $created_at],
            ['from' => 'USD',   'to' => 'GBP', 'rate' => 0.711178,  'created_at' => $created_at,    'updated_at' => $created_at],
            ['from' => 'USD',   'to' => 'EUR', 'rate' => 0.884872,  'created_at' => $created_at,    'updated_at' => $created_at],
        ]);
    }
}