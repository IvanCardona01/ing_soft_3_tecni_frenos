@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Pages', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="dashboard-fondo">

        <div class="min-h-[calc(100vh)] w-full flex flex-col items-center justify-center gap-6 md:flex-row md:gap-10">
            <a href="{{ route('dashboard.orderService', ['isEdit' => 'false']) }}"
                class="flex w-full max-w-[18rem] flex-row overflow-hidden rounded-[28px] shadow-md transition hover:shadow-lg md:w-[24rem] md:max-w-none h-[110px] md:h-[150px]">
                <div
                    class="flex h-full w-[100px] max-w-[100px] items-center justify-center bg-[#372C97] md:h-full md:w-[160px] md:max-w-[160px]">
                    <img src="{{ asset('icons/create-order-icon.svg') }}" alt="New Order"
                        class="w-[50px] h-[50px] md:w-[100px] md:h-[100px]">
                </div>
                <div
                    class="flex flex-1 items-center justify-center bg-[#F7DE0C] px-6 py-5 text-center text-[#1F1F1F] text-lg font-semibold md:text-xl">
                    <span>Crear nueva orden</span>
                </div>
            </a>

            <a href="{{ route('dashboard.orders') }}"
                class="flex w-full max-w-[18rem] flex-row overflow-hidden rounded-[28px] shadow-md transition hover:shadow-lg md:w-[24rem] md:max-w-none h-[110px] md:h-[150px]">
                <div
                    class="flex h-full w-[100px] max-w-[100px] items-center justify-center bg-[#372C97] md:h-full md:w-[160px] md:max-w-[160px]">
                    <img src="{{ asset('icons/see-orders-icon.svg') }}" alt="Orders"
                        class="w-[50px] h-[50px] md:w-[85px] md:h-[85px]">
                </div>
                <div
                    class="flex flex-1 items-center justify-center bg-[#F7DE0C] px-6 py-5 text-center text-[#1F1F1F] text-lg font-semibold md:text-xl">
                    <span>Ver órdenes</span>
                </div>
            </a>
        </div>
    </div>
@endsection
