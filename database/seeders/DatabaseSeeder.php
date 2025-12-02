<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@library.com',
            'role' => 'admin',
            'password' => 'password',
        ]);
    }
}
