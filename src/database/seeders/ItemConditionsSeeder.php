<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        DB::table('item_conditions')->upsert(
            [
                ['name' => '良好', 'created_at' => $now, 'updated_at' => $now],
                ['name' => '目立った傷や汚れなし', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'やや傷や汚れあり', 'created_at' => $now, 'updated_at' => $now],
                ['name' => '状態が悪い', 'created_at' => $now, 'updated_at' => $now],
            ],
            ['name'],
            ['updated_at']
        );
    }
}
