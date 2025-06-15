<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Futsal',
                'description' => 'Futsal courts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basketball',
                'description' => 'Basketball courts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tennis',
                'description' => 'Tennis courts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
