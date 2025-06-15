<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lapangans')->insert([
            [
                'name' => 'Lapangan A',
                'category_id' => 1,
                'description' => 'Lapangan indoor dengan fasilitas lengkap.',
                'price' => '150000',
                'photo' => 'lapangan_a.jpg',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lapangan B',
                'category_id' => 2,
                'description' => 'Lapangan outdoor dengan pemandangan indah.',
                'price' => '120000',
                'photo' => 'lapangan_b.jpg',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lapangan C',
                'category_id' => 1,
                'description' => 'Lapangan mini soccer dengan rumput sintetis.',
                'price' => '200000',
                'photo' => 'lapangan_c.jpg',
                'status' => 'unavailable',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
