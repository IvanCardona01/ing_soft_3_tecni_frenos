@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Orden Servicio', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="orders-background">
        <div class="xl:px-[74px] px-[20px] py-2 md:mt-[160px] mt-[100px] pb-20">
            <div
                class="flex items-center justify-end w-[280px] gap-3 md:mb-12 mb-6 cursor-pointer transition-transform duration-200 ease-in-out hover:scale-105 origin-right ml-auto">
                <span class="text-[#808080] font-light text-[20px]">Exportar tabla a:</span>
                <img src="{{ asset('icons/csv-icon.svg') }}" alt="exportar CSV" width="60" height="60">
            </div>
            <div class="flex flex-col md:flex-row w-full justify-between items-center gap-y-2">
                <div class="w-full md:max-w-[250px]">
                    <form method="GET" action="/buscar" class="relative">
                        <input type="text" name="placa" placeholder="Buscar por placa"
                            class="w-full border-2 border-indigo-700 rounded-xl py-3 pl-4 pr-12 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <img src="{{ asset('icons/searcher-icon.svg') }}" alt="Buscar" class="w-5 h-5" />
                        </button>
                    </form>
                </div>

                <div
                    class="flex flex-row md:flex-col lg:flex-row gap-2 md:gap-0 lg:gap-2 items-center w-full md:w-[160px] lg:w-[250px]">
                    <label for="date-from" class="block text-[18px] text-[#292D32]">Desde</label>

                    <div class="relative w-full">

                        <input type="date" id="date-from" value="2020-02-01"
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

                        <input type="date" id="date-until" value="2020-02-01"
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

                <select id="status-filter"
                    class="h-[50px] border-2 border-indigo-700 rounded-xl px-4 pr-12 w-full md:max-w-[250px]
           appearance-none bg-no-repeat bg-right-4 bg-center bg-contain
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    style="background-size: 20px; background-position: right 12px center; background-image: url('/icons/arrow-down.svg');">
                    <option value="todas">Todas</option>
                    <option value="abiertas">Abiertas</option>
                    <option value="asignadas">Asignadas</option>
                    <option value="cerradas">Cerradas</option>
                </select>

            </div>
            @if (isset($orders) && is_array($orders))
                <div class="flex flex-col gap-10 mt-10">
                    @foreach ($orders as $order)
                        <a href="{{ route('dashboard.orderService', ['isEdit' => 'true']) }}"
                            class="flex flex-col md:flex-row w-full rounded-[30px] overflow-hidden shadow-xl 
                    cursor-pointer transition-transform duration-300 ease-out hover:scale-[1.02] no-underline">
                            <div
                                class="bg-[#2A229F] text-white flex items-center justify-center px-8 py-6 md:px-10 md:py-6 w-full md:w-auto">
                                <span class="text-3xl md:text-4xl font-semibold tracking-wide text-center md:text-left">
                                    {{ $order['plate'] }}
                                </span>
                            </div>
                            <div
                                class="flex-1 bg-[#F5D900] flex flex-col md:flex-row md:items-center md:justify-between px-6 py-6 md:px-10">
                                <div class="flex flex-col text-center md:text-left">
                                    <span class="text-2xl md:text-3xl font-medium text-[#2A229F] leading-tight">
                                        {{ $order['customer_name'] }}
                                    </span>
                                    <span class="text-lg md:text-xl font-semibold text-[#2A229F] opacity-80 mt-1">
                                        {{ $order['vehicle_model'] }}
                                    </span>
                                </div>
                                <div class="flex flex-col items-center md:items-end mt-4 md:mt-0 gap-2">
                                    <span
                                        class="bg-white text-[#2A229F] font-semibold rounded-full px-5 py-2 
                               text-base md:text-lg shadow">
                                        {{ $order['date'] }}
                                    </span>
                                    <span class="text-2xl md:text-3xl font-medium text-[#2A229F]">
                                        {{ $order['status'] }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>


        {{-- <div
            class="container-content-main bg-transparent min-h-[calc(100vh-120px)] px-8 py-6 pb-24 md:px-5 md:py-5 md:pb-20 sm:px-3 sm:py-4 sm:pb-16 relative">

            <div class="flex justify-end items-center mb-2">
                <div
                    class="flex items-center gap-1 text-[0.95rem] bg-transparent border-none text-black font-medium cursor-pointer transition-colors duration-300 hover:text-primary">
                    <span>Exportar tabla a</span>
                    <img src="{{ asset('images/Group_15679.png') }}" alt="importar" width="60px" height="60px">
                </div>
            </div>

            <div
                class="flex justify-between items-center flex-wrap bg-gradient-to-r from-yellow-light to-[#f1f1fb] rounded-xl px-4 py-2.5 gap-x-8 gap-y-3 md:flex-col md:items-stretch md:gap-y-4 md:px-2.5 md:py-2 sm:px-2.5 sm:py-2 sm:gap-1.5">
                <div class="flex-1 min-w-[290px] max-w-[560px] flex items-center gap-2 md:min-w-full md:max-w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-gray-500">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" id="search-plate"
                        class="h-[42px] leading-[42px] border-[1.8px] border-primary rounded-xl px-3.5 text-[0.95rem] text-gray-600 bg-white transition-all duration-300 font-sans placeholder:text-gray-400 focus:outline-none focus:border-primary-light focus:ring-4 focus:ring-[rgba(59,45,176,0.18)] w-full md:w-full"
                        placeholder="Buscar por placa">
                </div>

                <div class="flex items-center gap-7 md:flex-wrap md:justify-start sm:flex-col sm:items-stretch sm:gap-2">
                    <div class="flex items-center gap-2.5 sm:w-full sm:justify-between">
                        <label for="date-from" class="text-sm text-[#1e1e1e] m-0">Desde</label>
                        <div>
                            <input type="date" id="date-from"
                                class="h-[42px] leading-[42px] border-[1.8px] border-primary rounded-xl px-3.5 text-[0.95rem] text-gray-600 bg-white transition-all duration-300 font-sans focus:outline-none focus:border-primary-light focus:ring-4 focus:ring-[rgba(59,45,176,0.18)] w-[220px] sm:w-[60%]"
                                value="2020-02-01">
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 sm:w-full sm:justify-between">
                        <label for="date-to" class="text-sm text-[#1e1e1e] m-0">Hasta</label>
                        <div>
                            <input type="date" id="date-to"
                                class="h-[42px] leading-[42px] border-[1.8px] border-primary rounded-xl px-3.5 text-[0.95rem] text-gray-600 bg-white transition-all duration-300 font-sans focus:outline-none focus:border-primary-light focus:ring-4 focus:ring-[rgba(59,45,176,0.18)] w-[220px] sm:w-[60%]"
                                value="2025-03-01">
                        </div>
                    </div>
                </div>

                <div class="w-40 md:w-full sm:w-full">
                    <select id="status-filter"
                        class="h-[42px] leading-[42px] border-[1.8px] border-primary rounded-xl px-3.5 text-[0.95rem] text-gray-600 bg-white transition-all duration-300 font-sans focus:outline-none focus:border-primary-light focus:ring-4 focus:ring-[rgba(59,45,176,0.18)] w-full cursor-pointer">
                        <option value="all">Todas</option>
                        <option value="abierto">Abierto</option>
                        <option value="asignada">Asignada</option>
                        <option value="cerrada">Cerrada</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-5 mt-6 relative z-10 sm:mt-4 sm:gap-3.5">
                @forelse($orders ?? [] as $order)
                    <div
                        class="flex flex-row rounded-[20px] shadow-[0_3px_6px_rgba(0,0,0,0.15)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_5px_10px_rgba(0,0,0,0.25)] sm:flex-col">
                        <div
                            class="w-[28%] bg-primary text-white flex items-center justify-center text-[42px] font-bold tracking-[2px] leading-none py-3 sm:w-full sm:py-2.5">
                            <span>{{ $order['plate'] }}</span>
                        </div>
                        <div
                            class="w-[72%] bg-yellow text-primary flex items-center justify-between px-6 py-4.5 sm:w-full sm:flex-col sm:items-start sm:px-3.5 sm:py-3">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-[22px] font-semibold text-primary leading-tight">
                                    {{ $order['customer_name'] }}</p>
                                <p
                                    class="text-sm font-semibold tracking-[0.5px] uppercase text-primary leading-tight opacity-90">
                                    {{ $order['vehicle_model'] }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2 sm:items-start sm:mt-2">
                                <span
                                    class="bg-white px-2.5 py-1 rounded-full text-xs font-semibold text-gray-600 leading-none shadow-[0_1px_2px_rgba(0,0,0,0.15)]">{{ $order['date'] }}</span>
                                <span class="font-bold text-xl capitalize text-primary leading-tight">
                                    {{ $order['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-row rounded-[20px] shadow-[0_3px_6px_rgba(0,0,0,0.15)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_5px_10px_rgba(0,0,0,0.25)] sm:flex-col">
                        <div
                            class="w-[28%] bg-primary text-white flex items-center justify-center text-[42px] font-bold tracking-[2px] leading-none py-3 sm:w-full sm:py-2.5">
                            <span>LXV28F</span>
                        </div>
                        <div
                            class="w-[72%] bg-yellow text-primary flex items-center justify-between px-6 py-4.5 sm:w-full sm:flex-col sm:items-start sm:px-3.5 sm:py-3">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-[22px] font-semibold text-primary leading-tight">Camilo Andrade Suárez</p>
                                <p
                                    class="text-sm font-semibold tracking-[0.5px] uppercase text-primary leading-tight opacity-90">
                                    TOYOTA TXL 2027</p>
                            </div>
                            <div class="flex flex-col items-end gap-2 sm:items-start sm:mt-2">
                                <span
                                    class="bg-white px-2.5 py-1 rounded-full text-xs font-semibold text-gray-600 leading-none shadow-[0_1px_2px_rgba(0,0,0,0.15)]">22/10/2025</span>
                                <span class="font-bold text-xl capitalize text-primary leading-tight">Abierto</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-row rounded-[20px] shadow-[0_3px_6px_rgba(0,0,0,0.15)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_5px_10px_rgba(0,0,0,0.25)] sm:flex-col">
                        <div
                            class="w-[28%] bg-primary text-white flex items-center justify-center text-[42px] font-bold tracking-[2px] leading-none py-3 sm:w-full sm:py-2.5">
                            <span>MTR59K</span>
                        </div>
                        <div
                            class="w-[72%] bg-yellow text-primary flex items-center justify-between px-6 py-4.5 sm:w-full sm:flex-col sm:items-start sm:px-3.5 sm:py-3">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-[22px] font-semibold text-primary leading-tight">Juliana Torres Bedoya</p>
                                <p
                                    class="text-sm font-semibold tracking-[0.5px] uppercase text-primary leading-tight opacity-90">
                                    TOYOTA TXL 2027</p>
                            </div>
                            <div class="flex flex-col items-end gap-2 sm:items-start sm:mt-2">
                                <span
                                    class="bg-white px-2.5 py-1 rounded-full text-xs font-semibold text-gray-600 leading-none shadow-[0_1px_2px_rgba(0,0,0,0.15)]">22/10/2025</span>
                                <span class="font-bold text-xl capitalize text-primary leading-tight">Asignada</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-row rounded-[20px] shadow-[0_3px_6px_rgba(0,0,0,0.15)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_5px_10px_rgba(0,0,0,0.25)] sm:flex-col">
                        <div
                            class="w-[28%] bg-primary text-white flex items-center justify-center text-[42px] font-bold tracking-[2px] leading-none py-3 sm:w-full sm:py-2.5">
                            <span>LXV28F</span>
                        </div>
                        <div
                            class="w-[72%] bg-yellow text-primary flex items-center justify-between px-6 py-4.5 sm:w-full sm:flex-col sm:items-start sm:px-3.5 sm:py-3">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-[22px] font-semibold text-primary leading-tight">Samuel Pineda Lozano</p>
                                <p
                                    class="text-sm font-semibold tracking-[0.5px] uppercase text-primary leading-tight opacity-90">
                                    TOYOTA TXL 2027</p>
                            </div>
                            <div class="flex flex-col items-end gap-2 sm:items-start sm:mt-2">
                                <span
                                    class="bg-white px-2.5 py-1 rounded-full text-xs font-semibold text-gray-600 leading-none shadow-[0_1px_2px_rgba(0,0,0,0.15)]">22/10/2025</span>
                                <span class="font-bold text-xl capitalize text-primary leading-tight">Cerrada</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div
                class="absolute left-0 bottom-0 w-full flex items-end justify-start gap-6 px-6 pb-2 pointer-events-none z-0 sm:px-3 sm:pb-1.5 sm:gap-3.5">
                <div class="max-w-[60px] sm:max-w-[45px]">
                    <img src="{{ asset('images/management_3.png') }}" alt="management_3"
                        class="block w-full h-auto opacity-95">
                </div>
                <div class="max-w-[120px] sm:max-w-[90px]">
                    <img src="{{ asset('images/Group_2.png') }}" alt="Group_2" class="block w-full h-auto opacity-95">
                </div>
            </div>
        </div> --}}
    </div>
@endsection
