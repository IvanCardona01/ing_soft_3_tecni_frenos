<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Quotation;
use App\Models\QuotationSegment;
use App\Models\QuotationItem;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderServiceController extends Controller
{
    /**
     * Muestra el formulario de creación de órdenes de servicio con un folio único.
     */
    public function create(Request $request): View
    {
        $folioNumber = $this->generateUniqueFolio();
        $isEdit = $request->query('isEdit', 'false') === 'true';

        // Obtener usuarios con rol mechanic
        $mechanics = User::whereHas('roles', function ($query) {
            $query->where('name', 'mechanic');
        })->get();

        return view('dashboard.orderService', compact('folioNumber', 'isEdit', 'mechanics'));
    }

    /**
     * Muestra el detalle de una orden existente.
     */
    public function show(Order $order): View
    {
        $order->load([
            'vehicle.client',
            'status',
            'quotation.segments.items'
        ]);

        $damageData = $this->parseDamageNotes($order->danos_preexistentes);

        // Obtener usuarios con rol mechanic
        $mechanics = User::whereHas('roles', function ($query) {
            $query->where('name', 'mechanic');
        })->get();

        return view('dashboard.orderServiceDetail', compact('order', 'damageData', 'mechanics'));
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
                    'vin' => !empty($validated['vehicle_vin']) ? strtoupper($validated['vehicle_vin']) : null,
                    'motor' => !empty($validated['vehicle_motor']) ? $validated['vehicle_motor'] : null,
                    'kilometraje' => $validated['vehicle_kilometraje'],
                    'observaciones' => !empty($validated['vehicle_observaciones']) ? $validated['vehicle_observaciones'] : null,
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
                'assigned_technician' => $validated['assigned_technician'] ?? null,
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

    public function validatePlate(Request $request)
    {
        $foundVehicle = Vehicle::with('client')->where('plate', strtoupper($request->input('plate', '')))->first();

        $lastOrder = null;
        if ($foundVehicle) {
            $lastOrder = Order::with(['vehicle.client'])
                ->where('vehicle_id', $foundVehicle->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        Log::info('Mensaje informativo', ['data' => $foundVehicle]);
        if ($foundVehicle) {
            return response()->json([
                'found' => true,
                'data' => $foundVehicle,
                'lastOrder' => $lastOrder
            ]);
        }

        return response()->json([
            'found' => false,
            'data' => null,
            'lastOrder' => null,
        ]);
    }

    /**
     * Actualiza una orden de servicio existente.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated, $request, $order) {
            // Actualizar datos del cliente
            $client = Client::updateOrCreate(
                ['document_number' => $validated['client_document_number']],
                [
                    'full_name' => $validated['client_full_name'],
                    'document_type' => $validated['client_document_type'],
                    'phone' => $validated['client_phone'],
                    'address' => $validated['client_address'],
                ]
            );

            // Actualizar datos del vehículo
            $vehicle = $client->vehicles()->updateOrCreate(
                ['plate' => strtoupper($validated['vehicle_plate'])],
                [
                    'brand' => $validated['vehicle_brand'],
                    'model' => $validated['vehicle_model'],
                    'year' => $validated['vehicle_year'],
                    'cilindraje' => $validated['vehicle_cilindraje'],
                    'vin' => !empty($validated['vehicle_vin']) ? strtoupper($validated['vehicle_vin']) : null,
                    'motor' => !empty($validated['vehicle_motor']) ? $validated['vehicle_motor'] : null,
                    'kilometraje' => $validated['vehicle_kilometraje'],
                    'observaciones' => !empty($validated['vehicle_observaciones']) ? $validated['vehicle_observaciones'] : null,
                ]
            );

            $testigos = $this->decodeJson($validated['testigos'] ?? null);
            $damageNotes = $this->formatDamageNotes($validated);

            // Actualizar la orden
            $order->update([
                'vehicle_id' => $vehicle->id,
                'assigned_technician' => $validated['assigned_technician'] ?? null,
                'driver_name' => $validated['driver_name'],
                'driver_phone' => $validated['driver_phone'],
                'driver_email' => $validated['driver_email'],
                'ingreso_en_grua' => (bool) $validated['ingreso_en_grua'],
                'testigos' => $testigos,
                'gasolina' => $validated['gasolina'],
                'danos_preexistentes' => $damageNotes,
            ]);

            // Guardar o actualizar la cotización
            $this->saveQuotation($order, $validated['quotation'] ?? []);

            return $order->fresh(['vehicle.client', 'status', 'quotation.segments.items']);
        });

        return redirect()
            ->route('dashboard.orderService.show', $order)
            ->with('status', "Orden actualizada correctamente. Folio Nª-{$order->folio_number}");
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

    /**
     * Parsea las notas de daños preexistentes desde JSON.
     */
    protected function parseDamageNotes(?string $json): array
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

    /**
     * Guarda o actualiza la cotización con sus segmentos e items.
     */
    protected function saveQuotation(Order $order, array $quotationData): void
    {
        // Crear o actualizar la cotización (siempre, incluso si está vacía)
        $quotation = Quotation::updateOrCreate(
            ['order_id' => $order->id],
            ['notes' => $quotationData['notes'] ?? null]
        );

        // Obtener IDs de segmentos existentes
        $existingSegmentIds = $quotation->segments()->pluck('id')->toArray();
        $receivedSegmentIds = [];

        // Procesar segmentos
        if (isset($quotationData['segments']) && is_array($quotationData['segments']) && !empty($quotationData['segments'])) {
            foreach ($quotationData['segments'] as $index => $segmentData) {
                // Validar que el segmento tenga nombre
                if (empty($segmentData['name']) || trim($segmentData['name']) === '') {
                    continue;
                }

                // Si tiene ID, actualizar; si no, crear nuevo
                if (!empty($segmentData['id'])) {
                    $segment = QuotationSegment::where('id', $segmentData['id'])
                        ->where('quotation_id', $quotation->id)
                        ->first();

                    if ($segment) {
                        $segment->update([
                            'name' => trim($segmentData['name']),
                            'position' => $index,
                        ]);
                    } else {
                        $segment = QuotationSegment::create([
                            'quotation_id' => $quotation->id,
                            'name' => trim($segmentData['name']),
                            'position' => $index,
                        ]);
                    }
                } else {
                    $segment = QuotationSegment::create([
                        'quotation_id' => $quotation->id,
                        'name' => trim($segmentData['name']),
                        'position' => $index,
                    ]);
                }

                $receivedSegmentIds[] = $segment->id;

                // Obtener IDs de items existentes del segmento
                $existingItemIds = $segment->items()->pluck('id')->toArray();
                $receivedItemIds = [];

                // Procesar items del segmento
                if (isset($segmentData['items']) && is_array($segmentData['items']) && !empty($segmentData['items'])) {
                    foreach ($segmentData['items'] as $itemData) {
                        // Validar que el item tenga nombre
                        if (empty($itemData['name']) || trim($itemData['name']) === '') {
                            continue;
                        }

                        // Determinar si está autorizado (checkbox marcado = '1', no marcado = no existe en el array)
                        $isAuthorized = isset($itemData['is_authorized']) && $itemData['is_authorized'] == '1';

                        // Si tiene ID, actualizar; si no, crear nuevo
                        if (!empty($itemData['id'])) {
                            $item = QuotationItem::where('id', $itemData['id'])
                                ->where('segment_id', $segment->id)
                                ->first();

                            if ($item) {
                                $item->update([
                                    'name' => trim($itemData['name']),
                                    'quantity' => (int) ($itemData['quantity'] ?? 1),
                                    'unit_value' => (float) ($itemData['unit_value'] ?? 0),
                                    'is_authorized' => $isAuthorized,
                                ]);
                            } else {
                                $item = QuotationItem::create([
                                    'segment_id' => $segment->id,
                                    'name' => trim($itemData['name']),
                                    'quantity' => (int) ($itemData['quantity'] ?? 1),
                                    'unit_value' => (float) ($itemData['unit_value'] ?? 0),
                                    'is_authorized' => $isAuthorized,
                                ]);
                            }
                        } else {
                            $item = QuotationItem::create([
                                'segment_id' => $segment->id,
                                'name' => trim($itemData['name']),
                                'quantity' => (int) ($itemData['quantity'] ?? 1),
                                'unit_value' => (float) ($itemData['unit_value'] ?? 0),
                                'is_authorized' => $isAuthorized,
                            ]);
                        }

                        $receivedItemIds[] = $item->id;
                    }
                }

                // Eliminar items que no están en los datos recibidos
                $itemsToDelete = array_diff($existingItemIds, $receivedItemIds);
                if (!empty($itemsToDelete)) {
                    QuotationItem::whereIn('id', $itemsToDelete)->delete();
                }
            }
        }

        // Eliminar segmentos que no están en los datos recibidos
        $segmentsToDelete = array_diff($existingSegmentIds, $receivedSegmentIds);
        if (!empty($segmentsToDelete)) {
            QuotationSegment::whereIn('id', $segmentsToDelete)->delete();
        }
    }

    /**
     * Genera un PDF de la cotización para enviar al cliente.
     */
    public function generateQuotationPdf(Order $order)
    {
        $order->load([
            'vehicle.client',
            'quotation.segments.items'
        ]);

        $quotation = $order->quotation;

        if (!$quotation) {
            return redirect()
                ->route('dashboard.orderService.show', $order)
                ->with('error', 'No hay cotización disponible para generar el PDF.');
        }

        // Preparar datos básicos del cliente
        $clientData = [
            'full_name' => $order->vehicle->client->full_name ?? 'N/A',
            'phone' => $order->vehicle->client->phone ?? 'N/A',
        ];

        // Preparar datos básicos del vehículo
        $vehicleData = [
            'brand' => $order->vehicle->brand ?? 'N/A',
            'model' => $order->vehicle->model ?? 'N/A',
            'year' => $order->vehicle->year ?? 'N/A',
            'plate' => $order->vehicle->plate ?? 'N/A',
        ];

        // Calcular totales
        $grandTotalAuthorized = 0;
        $grandTotalNotAuthorized = 0;
        $segmentsData = [];

        foreach ($quotation->segments as $segment) {
            $segmentTotalAuthorized = 0;
            $segmentTotalNotAuthorized = 0;
            $itemsData = [];

            foreach ($segment->items as $item) {
                $itemTotal = $item->quantity * $item->unit_value;
                $itemsData[] = [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit_value' => $item->unit_value,
                    'total' => $itemTotal,
                    'is_authorized' => $item->is_authorized,
                ];

                // Sumar según si está autorizado o no
                if ($item->is_authorized) {
                    $segmentTotalAuthorized += $itemTotal;
                } else {
                    $segmentTotalNotAuthorized += $itemTotal;
                }
            }

            $segmentsData[] = [
                'name' => $segment->name,
                'items' => $itemsData,
                'total_authorized' => $segmentTotalAuthorized,
                'total_not_authorized' => $segmentTotalNotAuthorized,
            ];

            $grandTotalAuthorized += $segmentTotalAuthorized;
            $grandTotalNotAuthorized += $segmentTotalNotAuthorized;
        }

        // Convertir logo a base64 para el PDF
        $logoPath = public_path('images/logo1.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $data = [
            'order' => $order,
            'quotation' => $quotation,
            'clientData' => $clientData,
            'vehicleData' => $vehicleData,
            'segments' => $segmentsData,
            'grandTotalAuthorized' => $grandTotalAuthorized,
            'grandTotalNotAuthorized' => $grandTotalNotAuthorized,
            'logoBase64' => $logoBase64,
        ];

        $pdf = Pdf::loadView('dashboard.quotation-pdf', $data);

        $fileName = 'Cotizacion_Orden_' . $order->folio_number . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
}
