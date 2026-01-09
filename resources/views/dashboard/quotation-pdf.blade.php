<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización - Orden {{ $order->folio_number }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; padding: 50px; }
        .segment { page-break-inside: avoid; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f3f4f6; padding: 12px; text-align: left; font-weight: bold; color: #372C97; border-bottom: 2px solid #372C97; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        .segment table tbody tr:last-child td { border-bottom: none; }
    </style>
</head>

<body>
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #372C97;">
        <div>
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="TecniFrenos Logo" style="max-width: 150px; height: auto;">
            @else
                <div style="font-size: 24px; font-weight: bold; color: #372C97;">TecniFrenos</div>
            @endif
        </div>
        <div style="text-align: right;">
            <h1 style="color: #372C97; font-size: 20px; margin-bottom: 3px;">COTIZACIÓN</h1>
            <p style="font-size: 11px; color: #666;">Mecánica Rápida T.F.Q</p>
        </div>
    </div>

    <!-- Information Card -->
    <div style="background-color: #f9fafb; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px;">
        <!-- Header con Folio y Fecha -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #372C97;">
            <div style="font-size: 10px;">
                <strong style="color: #372C97; margin-right: 5px;">Folio:</strong> Nª-{{ $order->folio_number }}
            </div>
            <div style="font-size: 10px;">
                <strong style="color: #372C97; margin-right: 5px;">Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}
            </div>
        </div>
        
        <!-- Contenido: Cliente y Vehículo en dos columnas -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <!-- Cliente -->
            <div style="font-size: 11px;">
                <h3 style="color: #372C97; font-size: 12px; margin-bottom: 8px; font-weight: bold;">Datos del Cliente</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 5px;">
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Nombre Completo:</strong> {{ $clientData['full_name'] }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Teléfono:</strong> {{ $clientData['phone'] }}
                    </div>
                </div>
            </div>

            <!-- Vehículo -->
            <div style="font-size: 11px;">
                <h3 style="color: #372C97; font-size: 12px; margin-bottom: 8px; font-weight: bold;">Datos del Vehículo</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 5px;">
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Marca:</strong> {{ $vehicleData['brand'] }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Modelo:</strong> {{ $vehicleData['model'] }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Año:</strong> {{ $vehicleData['year'] }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #372C97; display: inline-block; min-width: 90px; font-size: 11px;">Placa:</strong> {{ $vehicleData['plate'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quotation Section -->
    <div style="margin-top: 25px;">
        <h2 style="color: #372C97; font-size: 24px; margin-bottom: 25px; text-align: center; border-bottom: 4px solid #372C97; padding-bottom: 15px; font-weight: bold;">DETALLE DE LA COTIZACIÓN</h2>

        @if (count($segments) > 0)
            @foreach ($segments as $segment)
                <div class="segment">
                    <!-- Segment Header -->
                    <div style="background-color: #372C97; color: white; padding: 12px 15px; font-size: 16px; font-weight: bold; border-radius: 4px 4px 0 0;">
                        {{ $segment['name'] }}
                    </div>
                    
                    <!-- Items Table -->
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40%;">Servicio/Producto</th>
                                <th style="width: 10%; text-align: center;">Cantidad</th>
                                <th style="width: 20%; text-align: right;">Precio Unitario</th>
                                <th style="width: 15%; text-align: center;">Autorizado</th>
                                <th style="width: 15%; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($segment['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                                    <td style="text-align: right;">${{ number_format($item['unit_value'], 2, ',', '.') }}</td>
                                    <td style="text-align: center;">
                                        <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; background-color: {{ $item['is_authorized'] ? '#10b981' : '#ef4444' }}; color: white;">
                                            {{ $item['is_authorized'] ? 'SÍ' : 'NO' }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <strong>${{ number_format($item['total'], 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Segment Totals -->
                    <div style="background-color: #f3f4f6; padding: 12px 15px; text-align: right; font-weight: bold; font-size: 14px; border-top: 2px solid #372C97;">
                        <div style="margin-bottom: 5px;">
                            Total del Segmento (Autorizados): <strong style="color: #372C97; font-size: 16px;">${{ number_format($segment['total_authorized'], 2, ',', '.') }}</strong>
                        </div>
                        <div style="border-top: 1px solid #d1d5db; padding-top: 5px; margin-top: 5px;">
                            Total del Segmento (No Autorizados): <strong style="color: #ef4444; font-size: 16px;">${{ number_format($segment['total_not_authorized'], 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Grand Totals -->
            <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #372C97 0%, #2a1f7a 100%); color: white; border-radius: 8px; text-align: center;">
                <h3 style="font-size: 18px; margin-bottom: 10px;">TOTAL GENERAL (AUTORIZADOS)</h3>
                <div style="font-size: 32px; font-weight: bold; margin-bottom: 15px;">${{ number_format($grandTotalAuthorized, 2, ',', '.') }}</div>
                <div style="border-top: 2px solid rgba(255,255,255,0.3); padding-top: 15px; margin-top: 15px;">
                    <h3 style="font-size: 18px; margin-bottom: 10px;">TOTAL GENERAL (NO AUTORIZADOS)</h3>
                    <div style="font-size: 32px; font-weight: bold;">${{ number_format($grandTotalNotAuthorized, 2, ',', '.') }}</div>
                </div>
            </div>

            @if ($quotation->notes)
                <div style="margin-top: 30px; background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px;">
                    <h3 style="color: #856404; font-size: 14px; margin-bottom: 8px;">Notas de la Cotización:</h3>
                    <p>{{ $quotation->notes }}</p>
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 40px; color: #666;">
                <p>No hay items en la cotización.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; color: #666; font-size: 10px;">
        <p style="margin: 5px 0;"><strong>Mecánica Rápida T.F.Q - TecniFrenos</strong></p>
        <p style="margin: 5px 0;">Este documento es una cotización y requiere autorización del cliente para proceder con los trabajos.</p>
        <p style="margin: 5px 0;">Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>
