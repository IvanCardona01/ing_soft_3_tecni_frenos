<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero crear los roles
        $this->call([
            RoleSeeder::class,
            OrderStatusSeeder::class,
        ]);

        // Usuario Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'mauricio@example.com'],
            [
                'name' => 'Mauricio',
                'cedula' => '1234567890',
                'password' => Hash::make('Mauricio123*'),
            ]
        );

        // Asignar el rol de superadmin (sin duplicar si ya lo tiene)
        if (!$superadmin->hasRole('superadmin')) {
            $superadmin->assignRole('superadmin');
        }

        // Usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'cedula' => '0987654321',
                'password' => Hash::make('admin123*'),
            ]
        );

        // Asignar el rol de admin (sin duplicar si ya lo tiene)
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Usuario Mecánico
        $mechanic = User::firstOrCreate(
            ['email' => 'mecanico@gmail.com'],
            [
                'name' => 'Mecánico',
                'cedula' => '1122334455',
                'password' => Hash::make('mecanico123*'),
            ]
        );

        // Asignar el rol de mechanic (sin duplicar si ya lo tiene)
        if (!$mechanic->hasRole('mechanic')) {
            $mechanic->assignRole('mechanic');
        }
    }
}
