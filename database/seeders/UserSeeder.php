<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'name' => 'admin'
        ]);
        $admin = User::create([
            'name' => 'Admin Larasence',
            'email' => 'admin@example.test',
            'password' => Hash::make('123'),
        ]);
        $admin->assignRole($adminRole);

        $employeeRole = Role::create([
            'name' => 'employee'
        ]);
        $employee = User::create([
            'name' => 'Employee Larasence',
            'email' => 'employee@example.test',
            'password' => Hash::make('123'),
        ]);
        $employee->assignRole($employeeRole);
    }
}
