<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['pending', 'on_time', 'late', 'absent', 'permission'];

        for ($i = 0; $i < 60; $i++) {
            Attendance::create([
                'user_id' => rand(1, 20),
                'check_in' => Carbon::now()->subDays(rand(1, 30))->setTime(rand(7, 9), rand(0, 59)),
                'check_out' => Carbon::now()->subDays(rand(1, 30))->setTime(rand(16, 18), rand(0, 59)),
                'latitude' => -7.626966,
                'longitude' => 110.849205,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
