@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Orden Servicio', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')

    <div class="orders-background">
        <div class="xl:px-[74px] px-[20px] py-2 md:mt-[160px] mt-[100px] pb-20">
            <div
                class="flex items-center justify-end w-[280px] gap-3 md:mb-12 mb-6 cursor-pointer transition-transform duration-200 ease-in-out hover:scale-105 origin-right ml-auto">
                <span class="text-[#808080] font-light text-[20px]">Exportar tabla a:</span>
                <img src="{{ asset('icons/csv-icon.svg') }}" alt="exportar CSV" width="60" height="60">
            </div>
            <form method="GET" action="{{ route('dashboard.orders') }}" id="filters-form"
                class="flex flex-col md:flex-row w-full justify-between items-center gap-y-2">
                <div class="w-full md:max-w-[250px]">
                    <div class="relative">
                        <input type="text" name="placa" id="placa" value="{{ $filters['placa'] ?? '' }}"
                            placeholder="Buscar por placa"
                            class="w-full border-2 border-indigo-700 rounded-xl py-3 pl-4 pr-12 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <img src="{{ asset('icons/searcher-icon.svg') }}" alt="Buscar" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div
                    class="flex flex-row md:flex-col lg:flex-row gap-2 md:gap-0 lg:gap-2 items-center w-full md:w-[160px] lg:w-[250px]">
                    <label for="date-from" class="block text-[18px] text-[#292D32]">Desde</label>

                    <div class="relative w-full">
                        <input type="date" id="date-from" name="date-from" value="{{ $filters['date_from'] ?? '' }}"
                            class="w-full h-[50px] border-2 border-indigo-700 rounded-xl md:px-4
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                   [&::-webkit-calendar-picker-indicator]:opacity-0
                                   [&::-webkit-inner-spin-button]:hidden
                                   [&::-webkit-clear-button]:hidden" />

                        <button type="button" onclick="document.getElementById('date-from').showPicker();"
                            class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer">
                            <img src="/icons/calendar-icon.svg" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div
                    class="flex flex-row md:flex-col lg:flex-row gap-2 md:gap-0 lg:gap-2 items-center w-full  md:w-[160px] lg:w-[250px]">
                    <label for="date-until" class="block text-[18px] text-[#292D32]">Hasta</label>

                    <div class="relative w-full">
                        <input type="date" id="date-until" name="date-until" value="{{ $filters['date_until'] ?? '' }}"
                            class="w-full h-[50px] border-2 border-indigo-700 rounded-xl md:px-4
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                   [&::-webkit-calendar-picker-indicator]:opacity-0
                                   [&::-webkit-inner-spin-button]:hidden
                                   [&::-webkit-clear-button]:hidden" />

                        <button type="button" onclick="document.getElementById('date-until').showPicker();"
                            class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer">
                            <img src="/icons/calendar-icon.svg" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <select id="status-filter" name="status"
                    class="h-[50px] border-2 border-indigo-700 rounded-xl px-4 pr-12 w-full md:max-w-[250px]
           appearance-none bg-no-repeat bg-right-4 bg-center bg-contain
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    style="background-size: 20px; background-position: right 12px center; background-image: url('/icons/arrow-down.svg');">
                    <option value="todas" {{ ($filters['status'] ?? 'todas') === 'todas' ? 'selected' : '' }}>Todas
                    </option>
                    <option value="abiertas" {{ ($filters['status'] ?? '') === 'abiertas' ? 'selected' : '' }}>Abiertas
                    </option>
                    <option value="asignadas" {{ ($filters['status'] ?? '') === 'asignadas' ? 'selected' : '' }}>Asignadas
                    </option>
                    <option value="cerradas" {{ ($filters['status'] ?? '') === 'cerradas' ? 'selected' : '' }}>Cerradas
                    </option>
                </select>

            </form>
            <div class="flex flex-col gap-10 mt-10">
                @forelse($orders ?? [] as $order)
                    <a href="{{ route('dashboard.orderService.show', $order->id) }}"
                        class="flex flex-col md:flex-row w-full rounded-[30px] overflow-hidden shadow-xl 
                        cursor-pointer transition-transform duration-300 ease-out hover:scale-[1.02] no-underline">
                        <div
                            class="bg-[#2A229F] text-white flex items-center justify-center px-8 py-6 md:px-10 md:py-6 w-full md:w-auto">
                            <span class="text-3xl md:text-4xl font-semibold tracking-wide text-center md:text-left">
                                {{ $order->plate }}
                            </span>
                        </div>
                        <div
                            class="flex-1 bg-[#F5D900] flex flex-col md:flex-row md:items-center md:justify-between px-6 py-6 md:px-10">
                            <div class="flex flex-col text-center md:text-left">
                                <span class="text-2xl md:text-3xl font-medium text-[#2A229F] leading-tight">
                                    {{ $order->customer_name }}
                                </span>
                                <span class="text-lg md:text-xl font-semibold text-[#2A229F] opacity-80 mt-1">
                                    {{ $order->formatted_vehicle_model }}
                                </span>
                                <span class="text-sm text-[#2A229F] opacity-70 mt-1">
                                    Folio: {{ $order->folio_number }}
                                </span>
                            </div>
                            <div class="flex flex-col items-center md:items-end mt-4 md:mt-0 gap-2">
                                <span
                                    class="bg-white text-[#2A229F] font-semibold rounded-full px-5 py-2 
                           text-base md:text-lg shadow">
                                    {{ $order->formatted_date }}
                                </span>
                                <span class="text-2xl md:text-3xl font-medium text-[#2A229F]">
                                    {{ $order->status_name }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 px-4 bg-white rounded-[30px] shadow-xl">
                        <svg class="w-24 h-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-xl font-semibold text-gray-600 mb-2">No se encontraron órdenes</p>
                        <p class="text-gray-500 text-center">
                            @if ($hasFilters ?? false)
                                No hay órdenes que coincidan con los filtros aplicados.
                            @else
                                Aún no hay órdenes registradas en el sistema.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filters-form');
            const statusFilter = document.getElementById('status-filter');
            const dateFrom = document.getElementById('date-from');
            const dateUntil = document.getElementById('date-until');
            const placaInput = document.getElementById('placa');

            // Aplicar filtros automáticamente cuando cambian las fechas o el estado
            [statusFilter, dateFrom, dateUntil].forEach(element => {
                if (element) {
                    element.addEventListener('change', () => {
                        form.submit();
                    });
                }
            });

            // Para la búsqueda por placa, usar un debounce para evitar demasiadas peticiones
            let searchTimeout;
            if (placaInput) {
                placaInput.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Solo buscar si el campo tiene al menos 2 caracteres o está vacío
                        if (placaInput.value.length >= 2 || placaInput.value.length === 0) {
                            form.submit();
                        }
                    }, 500);
                });

                // Permitir búsqueda inmediata al presionar Enter
                placaInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        form.submit();
                    }
                });
            }
        });
    </script>
@endsection
