<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('1010!'),
            'role' => 'administrator',
        ]);

        User::create([
            'name' => 'Usuario Regular',
            'email' => 'user@example.com',
            'password' => Hash::make('1010!'),
            'role' => 'user',
        ]);
    }
}
