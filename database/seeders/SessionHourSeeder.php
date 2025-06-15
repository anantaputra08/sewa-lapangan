<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = [
            ['start_time' => '08:00:00', 'end_time' => '09:00:00', 'description' => 'Sesi Pagi 1'],
            ['start_time' => '09:00:00', 'end_time' => '10:00:00', 'description' => 'Sesi Pagi 2'],
            ['start_time' => '10:00:00', 'end_time' => '11:00:00', 'description' => 'Sesi Pagi 3'],
            ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'description' => 'Sesi Siang 1'],
            ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'description' => 'Sesi Siang 2'],
            ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'description' => 'Sesi Siang 3'],
            ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'description' => 'Sesi Sore 1'],
            ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'description' => 'Sesi Sore 2'],
            ['start_time' => '17:00:00', 'end_time' => '18:00:00', 'description' => 'Sesi Sore 3'],
            ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'description' => 'Sesi Malam 1'],
            ['start_time' => '19:00:00', 'end_time' => '20:00:00', 'description' => 'Sesi Malam 2'],
            ['start_time' => '20:00:00', 'end_time' => '21:00:00', 'description' => 'Sesi Malam 3'],
        ];

        foreach ($sessions as $session) {
            DB::table('session_hours')->insert([
                'description' => $session['description'],
                'day_id' => null,
                'start_time' => $session['start_time'],
                'end_time' => $session['end_time'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
