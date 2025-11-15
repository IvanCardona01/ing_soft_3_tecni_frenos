<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Orders extends Controller
{
    public function OrderAdmin() {
        // Datos de ejemplo de órdenes (reemplazar con datos reales de la base de datos)
        $orders = [
            [
                'plate' => 'LXV28F',
                'customer_name' => 'Camilo Andrade Suárez',
                'vehicle_model' => 'TOYOTA TXL 2027',
                'date' => '22/10/2025',
                'status' => 'Abierto'
            ],
            [
                'plate' => 'MTR59K',
                'customer_name' => 'Juliana Torres Bedoya',
                'vehicle_model' => 'TOYOTA TXL 2027',
                'date' => '22/10/2025',
                'status' => 'Asignada'
            ],
            [
                'plate' => 'LXV28F',
                'customer_name' => 'Samuel Pineda Lozano',
                'vehicle_model' => 'TOYOTA TXL 2027',
                'date' => '22/10/2025',
                'status' => 'Cerrada'
            ]
        ];

        return view('dashboard.orderadmin', compact('orders'));
    }
}
