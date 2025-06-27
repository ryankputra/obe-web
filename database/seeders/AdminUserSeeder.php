<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // or your actual User model namespace

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'master@gmail.com'],
            [
                'name' => 'master',
                'password' => Hash::make('12345678'), // Change password as needed
                'role' => 'admin', // If you use a role column
            ]
        );
    }
}
