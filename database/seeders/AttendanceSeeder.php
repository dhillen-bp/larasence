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
        $statuses = ['pending', 'on_time', 'late', 'absent'];

        for ($i = 0; $i < 20; $i++) {
            $day = Carbon::now();

            Attendance::create([
                'user_id'    => rand(1, 20),
                'check_in'   => $day->copy()->setTime(9, 0, 0),
                'check_out'  => $day->copy()->setTime(17, 0, 0),
                'latitude'   => -7.626966,
                'longitude'  => 110.849205,
                'status'     => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
