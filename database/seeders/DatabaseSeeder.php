<?php

namespace Database\Seeders;

use App\Models\SessionHour;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('1234'),
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
            'photo' => 'default.jpg',
            'role' => 'user',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('1234'),
            'phone' => '081234567891',
            'address' => 'Jl. Test No. 123',
            'photo' => 'default.jpg',
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->call([
            CategorySeeder::class,
            LapanganSeeder::class,
            DaySeeder::class,
            SessionHourSeeder::class,
            LapanganStatusSeeder::class,
        ]);
    }
}
