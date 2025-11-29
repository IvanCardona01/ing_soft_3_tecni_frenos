<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Muestra la lista de órdenes con filtros aplicados.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['vehicle.client', 'status', 'user'])
            ->latest('created_at');

        // Filtro por placa
        if ($request->filled('placa')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('plate', 'LIKE', '%' . strtoupper($request->placa) . '%');
            });
        }

        // Filtro por rango de fechas
        if ($request->filled('date-from')) {
            $query->whereDate('created_at', '>=', $request->input('date-from'));
        }

        if ($request->filled('date-until')) {
            $query->whereDate('created_at', '<=', $request->input('date-until'));
        }

        // Filtro por estado
        if ($request->filled('status') && $request->status !== 'todas') {
            $statusSlug = match ($request->status) {
                'abiertas' => 'abierto',
                'asignadas' => 'asignado',
                'cerradas' => 'cerrado',
                default => $request->status,
            };

            $query->whereHas('status', function ($q) use ($statusSlug) {
                $q->where('slug', $statusSlug);
            });
        }

        $orders = $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'folio_number' => $order->folio_number,
                'plate' => $order->vehicle->plate ?? 'N/A',
                'customer_name' => $order->vehicle->client->full_name ?? 'N/A',
                'vehicle_model' => $this->formatVehicleModel($order->vehicle),
                'date' => $order->created_at->format('d/m/Y'),
                'status' => $order->status->name ?? 'Sin estado',
                'status_slug' => $order->status->slug ?? null,
            ];
        });

        // Obtener estados para el filtro
        $statuses = OrderStatus::orderBy('id')->get();

        return view('dashboard.orders', [
            'orders' => $orders,
            'statuses' => $statuses,
            'filters' => [
                'placa' => $request->input('placa', ''),
                'date_from' => $request->input('date-from', ''),
                'date_until' => $request->input('date-until', ''),
                'status' => $request->input('status', 'todas'),
            ],
        ]);
    }

    /**
     * Formatea el modelo del vehículo para mostrar.
     */
    private function formatVehicleModel($vehicle): string
    {
        if (!$vehicle) {
            return 'N/A';
        }

        $parts = array_filter([
            strtoupper($vehicle->brand ?? ''),
            strtoupper($vehicle->model ?? ''),
            $vehicle->year ?? '',
        ]);

        return !empty($parts) ? implode(' ', $parts) : 'N/A';
    }
}
