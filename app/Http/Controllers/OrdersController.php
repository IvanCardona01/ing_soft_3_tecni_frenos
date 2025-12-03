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

        $orders = $query->get();

        // Determinar si hay filtros aplicados
        $hasFilters = $this->hasActiveFilters($request);

        return view('dashboard.orders', [
            'orders' => $orders,
            'filters' => [
                'placa' => $request->input('placa', ''),
                'date_from' => $request->input('date-from', ''),
                'date_until' => $request->input('date-until', ''),
                'status' => $request->input('status', 'todas'),
            ],
            'hasFilters' => $hasFilters,
        ]);
    }


    /**
     * Verifica si hay filtros activos en la solicitud.
     */
    private function hasActiveFilters(Request $request): bool
    {
        return $request->filled('placa') ||
            $request->filled('date-from') ||
            $request->filled('date-until') ||
            ($request->filled('status') && $request->input('status') !== 'todas');
    }

    /**
     * Parsea las notas de daños preexistentes desde JSON.
     */
    private function parseDamageNotes(?string $json): array
    {
        if (blank($json)) {
            return [
                'front' => ['notes' => '', 'points' => []],
                'behind' => ['notes' => '', 'points' => []],
                'left_side' => ['notes' => '', 'points' => []],
                'right_side' => ['notes' => '', 'points' => []],
            ];
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [
                'front' => ['notes' => '', 'points' => []],
                'behind' => ['notes' => '', 'points' => []],
                'left_side' => ['notes' => '', 'points' => []],
                'right_side' => ['notes' => '', 'points' => []],
            ];
        }

        // Asegurar que todas las secciones existan
        $sections = ['front', 'behind', 'left_side', 'right_side'];
        $result = [];
        foreach ($sections as $section) {
            $result[$section] = [
                'notes' => $decoded[$section]['notes'] ?? '',
                'points' => $decoded[$section]['points'] ?? [],
            ];
        }

        return $result;
    }
}
