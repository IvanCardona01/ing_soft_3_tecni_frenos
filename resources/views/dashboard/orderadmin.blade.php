@extends('layouts.vertical', [ 'title' => 'Dashboard', 'sub_title' => 'Orden Servicio', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orden-servicio.css') }}">
@endpush

@section('content')
    <div class="dashboard-fondo">
        <div class="container-content-main p-8 order-admin-container">

            <div class="order-admin-header">
                <div class="export-button">
                    <span>Exportar tabla a</span>
                    
                        <img src="{{ asset('images/Group_15679.png') }}" alt="importar" width="60px" height="60px">
                   
                </div>
            </div>

            <div class="order-filters">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="search-icon">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="search-plate" class="search-input" placeholder="Buscar por placa">
                </div>

                <div class="date-filters">
                    <div class="date-input-group">
                        <label for="date-from">Desde</label>
                        <div class="date-input-wrapper">
                            <input type="date" id="date-from" class="date-input" value="2020-02-01">
                            
                        </div>
                    </div>

                    <div class="date-input-group">
                        <label for="date-to">Hasta</label>
                        <div class="date-input-wrapper">
                            <input type="date" id="date-to" class="date-input" value="2025-03-01">
                            
                        </div>
                    </div>
                </div>

                <div class="status-filter">
                    <select id="status-filter" class="status-select">
                        <option value="all">Todas</option>
                        <option value="abierto">Abierto</option>
                        <option value="asignada">Asignada</option>
                        <option value="cerrada">Cerrada</option>
                    </select>
                </div>
            </div>

            <div class="orders-list">
                @forelse($orders ?? [] as $order)
                    <div class="order-card">
                        <div class="order-card-left">
                            <span class="order-plate">{{ $order['plate'] }}</span>
                        </div>
                        <div class="order-card-right">
                            <div class="order-info">
                                <p class="order-customer">{{ $order['customer_name'] }}</p>
                                <p class="order-vehicle">{{ $order['vehicle_model'] }}</p>
                                <div class="order-meta">
                                    <span class="order-date">{{ $order['date'] }}</span>
                                    <span class="order-status status-{{ strtolower($order['status']) }}">
                                        {{ $order['status'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="order-card">
                        <div class="order-card-left">
                            <span class="order-plate">LXV28F</span>
                        </div>
                        <div class="order-card-right">
                            <div class="order-info">
                                <p class="order-customer">Camilo Andrade Suárez</p>
                                <p class="order-vehicle">TOYOTA TXL 2027</p>
                                <div class="order-meta">
                                    <span class="order-date">22/10/2025</span>
                                    <span class="order-status status-abierto">Abierto</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="order-card-left">
                            <span class="order-plate">MTR59K</span>
                        </div>
                        <div class="order-card-right">
                            <div class="order-info">
                                <p class="order-customer">Juliana Torres Bedoya</p>
                                <p class="order-vehicle">TOYOTA TXL 2027</p>
                                <div class="order-meta">
                                    <span class="order-date">22/10/2025</span>
                                    <span class="order-status status-asignada">Asignada</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="order-card-left">
                            <span class="order-plate">LXV28F</span>
                        </div>
                        <div class="order-card-right">
                            <div class="order-info">
                                <p class="order-customer">Samuel Pineda Lozano</p>
                                <p class="order-vehicle">TOYOTA TXL 2027</p>
                                <div class="order-meta">
                                    <span class="order-date">22/10/2025</span>
                                    <span class="order-status status-cerrada">Cerrada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="order-imagen">
                <div class="order-imagen-container engranaje-pequeño-orden">
                    <img src="{{ asset('images/management_3.png') }}" alt="management_3">
                </div>
                <div class="order-imagen-container engranaje-grande-orden">
                    <img src="{{ asset('images/Group_2.png') }}" alt="Group_2">
                </div>
            </div>
        </div>
    </div>
@endsection