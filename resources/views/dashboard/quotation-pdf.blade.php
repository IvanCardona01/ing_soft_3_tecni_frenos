<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización - Orden {{ $order->folio_number }}</title>
    <style>
        @page {
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #372C97;
        }

        .logo {
            max-width: 200px;
            height: auto;
        }

        .header-info {
            text-align: right;
        }

        .header-info h1 {
            color: #372C97;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header-info p {
            font-size: 14px;
            color: #666;
        }

        .document-info {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .document-info h2 {
            color: #372C97;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item strong {
            color: #372C97;
            display: inline-block;
            min-width: 120px;
        }

        .client-section, .vehicle-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .client-section h3, .vehicle-section h3 {
            color: #372C97;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 2px solid #372C97;
            padding-bottom: 5px;
        }

        .quotation-section {
            margin-top: 30px;
        }

        .quotation-section h2 {
            color: #372C97;
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #372C97;
            padding-bottom: 10px;
        }

        .quotation-notes {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .quotation-notes h3 {
            color: #856404;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .segment {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .segment-header {
            background-color: #372C97;
            color: white;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px 4px 0 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: white;
        }

        .items-table thead {
            background-color: #f3f4f6;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #372C97;
            border-bottom: 2px solid #372C97;
        }

        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .authorized-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .authorized-yes {
            background-color: #10b981;
            color: white;
        }

        .authorized-no {
            background-color: #ef4444;
            color: white;
        }

        .segment-total {
            background-color: #f3f4f6;
            padding: 12px 15px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #372C97;
        }

        .grand-total {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #372C97 0%, #2a1f7a 100%);
            color: white;
            border-radius: 8px;
            text-align: center;
        }

        .grand-total h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .grand-total .amount {
            font-size: 32px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .footer p {
            margin: 5px 0;
        }

        @media print {
            .segment {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div>
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="TecniFrenos Logo" class="logo">
            @else
                <div style="font-size: 24px; font-weight: bold; color: #372C97;">TecniFrenos</div>
            @endif
        </div>
        <div class="header-info">
            <h1>COTIZACIÓN</h1>
            <p>Mecánica Rápida T.F.Q</p>
        </div>
    </div>

    <!-- Document Info -->
    <div class="document-info">
        <h2>Información del Documento</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Número de Folio:</strong> Nª-{{ $order->folio_number }}
            </div>
            <div class="info-item">
                <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <strong>Estado:</strong> {{ $order->status->name ?? 'Sin estado' }}
            </div>
            <div class="info-item">
                <strong>Ingreso en Grúa:</strong> {{ $order->ingreso_en_grua ? 'Sí' : 'No' }}
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="client-section">
        <h3>Datos del Cliente</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Nombre Completo:</strong> {{ $order->vehicle->client->full_name ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Documento:</strong> {{ strtoupper($order->vehicle->client->document_type ?? '') }} {{ $order->vehicle->client->document_number ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Teléfono:</strong> {{ $order->vehicle->client->phone ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Dirección:</strong> {{ $order->vehicle->client->address ?? 'N/A' }}
            </div>
        </div>
        <div style="margin-top: 15px;">
            <div class="info-item">
                <strong>Conductor:</strong> {{ $order->driver_name ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Teléfono Conductor:</strong> {{ $order->driver_phone ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Email Conductor:</strong> {{ $order->driver_email ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Vehicle Information -->
    <div class="vehicle-section">
        <h3>Datos del Vehículo</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Marca:</strong> {{ $order->vehicle->brand ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Modelo:</strong> {{ $order->vehicle->model ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Año:</strong> {{ $order->vehicle->year ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Placa:</strong> {{ $order->vehicle->plate ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Cilindraje:</strong> {{ $order->vehicle->cilindraje ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>VIN:</strong> {{ $order->vehicle->vin ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Motor:</strong> {{ $order->vehicle->motor ?? 'N/A' }}
            </div>
            <div class="info-item">
                <strong>Kilometraje:</strong> {{ number_format($order->vehicle->kilometraje ?? 0, 0, ',', '.') }} km
            </div>
        </div>
        @if($order->vehicle->observaciones)
            <div style="margin-top: 15px;">
                <strong>Observaciones del Vehículo:</strong>
                <p style="margin-top: 5px; padding: 10px; background-color: white; border-radius: 4px;">
                    {{ $order->vehicle->observaciones }}
                </p>
            </div>
        @endif
    </div>

    <!-- Quotation Section -->
    <div class="quotation-section">
        <h2>DETALLE DE LA COTIZACIÓN</h2>

        @if($quotation->notes)
            <div class="quotation-notes">
                <h3>Notas de la Cotización:</h3>
                <p>{{ $quotation->notes }}</p>
            </div>
        @endif

        @if(count($segments) > 0)
            @foreach($segments as $segment)
                <div class="segment">
                    <div class="segment-header">
                        {{ $segment['name'] }}
                    </div>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Servicio/Producto</th>
                                <th class="text-center" style="width: 10%;">Cantidad</th>
                                <th class="text-right" style="width: 20%;">Precio Unitario</th>
                                <th class="text-center" style="width: 15%;">Autorizado</th>
                                <th class="text-right" style="width: 15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($segment['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-right">${{ number_format($item['unit_value'], 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="authorized-badge {{ $item['is_authorized'] ? 'authorized-yes' : 'authorized-no' }}">
                                            {{ $item['is_authorized'] ? 'SÍ' : 'NO' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <strong>${{ number_format($item['total'], 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="segment-total">
                        Total del Segmento (Autorizados): <strong style="color: #372C97; font-size: 16px;">${{ number_format($segment['total'], 2, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach

            <!-- Grand Total -->
            <div class="grand-total">
                <h3>TOTAL GENERAL (AUTORIZADOS)</h3>
                <div class="amount">${{ number_format($grandTotal, 2, ',', '.') }}</div>
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #666;">
                <p>No hay items en la cotización.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Mecánica Rápida T.F.Q - TecniFrenos</strong></p>
        <p>Este documento es una cotización y requiere autorización del cliente para proceder con los trabajos.</p>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

