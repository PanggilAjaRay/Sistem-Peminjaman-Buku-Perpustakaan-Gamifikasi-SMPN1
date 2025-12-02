<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Pustakawan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create student user for testing
        User::create([
            'name' => 'Siswa Demo',
            'email' => 'siswa@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
