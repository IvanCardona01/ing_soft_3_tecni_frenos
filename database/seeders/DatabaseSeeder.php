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
        User::updateOrCreate(
            ['email' => 'mauricio@example.com'],
            [
                'name' => 'Mauricio',
                'cedula' => '1234567890',
                'password' => Hash::make('Mauricio123*'),
            ]
        );

        $this->call([
            OrderStatusSeeder::class,
        ]);
    }
}
