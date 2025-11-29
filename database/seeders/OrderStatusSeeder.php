<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Abierto', 'slug' => 'abierto'],
            ['name' => 'Asignado', 'slug' => 'asignado'],
            ['name' => 'Cerrado', 'slug' => 'cerrado'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::firstOrCreate(
                ['slug' => $status['slug']],
                ['name' => $status['name']]
            );
        }
    }
}

