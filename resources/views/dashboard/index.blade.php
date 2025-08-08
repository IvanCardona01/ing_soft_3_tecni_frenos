@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="dashboard-fondo">

        <div class="container-content-main p-8">
            <div class="container_card">
                <a href="{{route('dashboard.orderService')}}" class="card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                        stroke="#f7de0c" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clipboard-pen-line-icon lucide-clipboard-pen-line">
                        <rect width="8" height="4" x="8" y="2" rx="1" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5" />
                        <path d="M16 4h2a2 2 0 0 1 1.73 1" />
                        <path d="M8 18h1" />
                        <path
                            d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                    </svg>
                    <p class="text-gray-600">Crear nueva orden</p>
                </a>
                
                <a href="{{route('dashboard.orders')}}" class="card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                        stroke="#f7de0c" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-folder-open-dot-icon lucide-folder-open-dot">
                        <path
                            d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2" />
                        <circle cx="14" cy="15" r="1" />
                    </svg>
                    <p class="text-gray-600">Ver órdenes</p>
                </a>
            </div>
        </div>

    </div>
@endsection
