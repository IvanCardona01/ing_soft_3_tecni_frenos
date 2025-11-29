<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderServiceController extends Controller
{
    /**
     * Muestra el formulario de creación de órdenes de servicio con un folio único.
     */
    public function create(Request $request): View
    {
        $folioNumber = $this->generateUniqueFolio();
        $isEdit = $request->query('isEdit', 'false') === 'true';

        return view('dashboard.orderService', compact('folioNumber', 'isEdit'));
    }

    /**
     * Persiste la orden de servicio con toda la información relacionada.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $request) {
            $client = Client::updateOrCreate(
                ['document_number' => $validated['client_document_number']],
                [
                    'full_name' => $validated['client_full_name'],
                    'document_type' => $validated['client_document_type'],
                    'phone' => $validated['client_phone'],
                    'address' => $validated['client_address'],
                    'driver_name' => $validated['driver_name'],
                    'driver_phone' => $validated['driver_phone'],
                    'email' => $validated['driver_email'],
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

            return Order::create([
                'folio_number' => $validated['folio_number'],
                'user_id' => $request->user()->id,
                'vehicle_id' => $vehicle->id,
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

    public function validatePlate(Request $request): JsonResponse
    {
        $plate = strtoupper($request->input('plate', ''));

        if ($plate === 'ABC123') {
            return response()->json([
                'found' => true,
                'data' => [
                    'client_full_name' => 'Juan Pérez',
                    'client_document_type' => 'cc',
                    'client_document_number' => '1234567890',
                    'client_phone' => '3001234567',
                    'client_address' => 'Calle 123 #45-67',
                    'driver_name' => 'Juan Pérez',
                    'driver_phone' => '3001234567',
                    'driver_email' => 'juan@example.com',
                    'vehicle_brand' => 'Toyota',
                    'vehicle_model' => 'Corolla',
                    'vehicle_year' => 2020,
                    'vehicle_plate' => 'ABC123',
                    'vehicle_cilindraje' => '1600',
                    'vehicle_vin' => '1HGBH41JXMN109186',
                    'vehicle_motor' => '1.6L',
                    'vehicle_kilometraje' => 50000,
                ]
            ]);
        }

        return response()->json([
            'found' => false,
            'data' => null
        ]);
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
