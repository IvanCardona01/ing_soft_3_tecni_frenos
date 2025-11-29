<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderServiceController extends Controller
{
    /**
     * Muestra el formulario de creación de órdenes de servicio con un folio único.
     */
    public function create(): View
    {
        $folioNumber = $this->generateUniqueFolio();

        return view('dashboard.orderService', compact('folioNumber'));
    }

    /**
     * Persiste la orden de servicio con toda la información relacionada.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $request) {
            // Guardar solo los datos del cliente (propietario), sin datos del conductor
            $client = Client::updateOrCreate(
                ['document_number' => $validated['client_document_number']],
                [
                    'full_name' => $validated['client_full_name'],
                    'document_type' => $validated['client_document_type'],
                    'phone' => $validated['client_phone'],
                    'address' => $validated['client_address'],
                ]
            );

            $vehicle = $client->vehicles()->updateOrCreate(
                ['plate' => strtoupper($validated['vehicle_plate'])],
                [
                    'brand' => $validated['vehicle_brand'],
                    'model' => $validated['vehicle_model'],
                    'year' => $validated['vehicle_year'],
                    'cilindraje' => $validated['vehicle_cilindraje'],
                    'vin' => strtoupper($validated['vehicle_vin']),
                    'motor' => $validated['vehicle_motor'],
                    'kilometraje' => $validated['vehicle_kilometraje'],
                    'observaciones' => $validated['vehicle_observaciones'] ?? null,
                ]
            );

            $testigos = $this->decodeJson($validated['testigos'] ?? null);
            $damageNotes = $this->formatDamageNotes($validated);

            // Obtener el estado "Abierto" por defecto
            $statusAbierto = OrderStatus::where('slug', 'abierto')->first();

            // Guardar los datos del conductor en la orden para mantener el historial
            return Order::create([
                'folio_number' => $validated['folio_number'],
                'user_id' => $request->user()->id,
                'vehicle_id' => $vehicle->id,
                'status_id' => $statusAbierto->id,
                'driver_name' => $validated['driver_name'],
                'driver_phone' => $validated['driver_phone'],
                'driver_email' => $validated['driver_email'],
                'ingreso_en_grua' => (bool) $validated['ingreso_en_grua'],
                'testigos' => $testigos,
                'gasolina' => $validated['gasolina'],
                'danos_preexistentes' => $damageNotes,
            ]);
        });

        return redirect()
            ->route('dashboard.orderService')
            ->with('status', "Orden creada correctamente. Folio Nª-{$order->folio_number}");
    }

    /**
     * Genera un folio de seis dígitos que no exista en la base de datos.
     */
    protected function generateUniqueFolio(): string
    {
        do {
            $folio = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Order::where('folio_number', $folio)->exists());

        return $folio;
    }

    protected function decodeJson(?string $json): ?array
    {
        if (blank($json)) {
            return null;
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : null;
    }

    protected function formatDamageNotes(array $data): ?string
    {
        $notesMap = [
            'front' => 'observation_damage_front',
            'behind' => 'observation_damage_behind',
            'left_side' => 'observation_damage_left_side',
            'right_side' => 'observation_damage_right_side',
        ];

        $sections = [];

        foreach ($notesMap as $section => $noteKey) {
            $text = $data[$noteKey] ?? null;
            $points = $this->decodeJson($data["damage_points_{$section}"] ?? null);

            if (filled($text) || !empty($points)) {
                $sections[$section] = array_filter([
                    'notes' => filled($text) ? $text : null,
                    'points' => !empty($points) ? $points : null,
                ]);
            }
        }

        return empty($sections) ? null : json_encode($sections);
    }
}

