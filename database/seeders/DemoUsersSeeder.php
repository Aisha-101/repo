<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administratorius',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Registruotas Naudotojas',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Svečias',
            'email' => 'guest@example.com',
            'password' => Hash::make('guest123'),
            'role' => 'guest',
        ]);
    }
}
