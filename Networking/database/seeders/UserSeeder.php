<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadministrador
        User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // Administrador
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Usuario normal
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Usuario Regular',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Más usuarios de prueba
        User::firstOrCreate(
            ['email' => 'juan@test.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'maria@test.com'],
            [
                'name' => 'María García',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
