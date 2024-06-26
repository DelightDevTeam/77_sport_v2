<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreeDLimitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('three_d_d_limits')->insert([
            [
                'three_d_limit' => '500',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
