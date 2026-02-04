<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            [
                'email' => 'admin@system.com',
            ],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );
    }
}