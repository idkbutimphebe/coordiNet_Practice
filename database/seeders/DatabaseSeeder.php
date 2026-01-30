<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        // --- Seed the admin first ---
        $this->call([
            AdminUserSeeder::class,
        ]);

        // --- Seed a test user ---

User::updateOrCreate(
    ['email' => 'test@example.com'], // Check if exists
    [
        'name' => 'Test User',
        'password' => bcrypt('password123'), // Optional password
        'role' => 'user',
    ]
);


    }
}
