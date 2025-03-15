<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => "Employee $i",
                'email' => "employee$i@example.test",
                'password' => Hash::make('123'),
            ]);

            $user->assignRole('employee');
        }
    }
}
