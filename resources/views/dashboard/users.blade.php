@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Gestionar Usuarios', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="dashboard-fondo">
        <div class="container-content-main md:p-8" style="background-color: #ffffff91; height: 100%;">
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-[#372C97] mb-2">Gestión de Usuarios</h1>
                <p class="text-base md:text-lg text-gray-600">Lista de usuarios del sistema</p>
            </div>

            @if ($users->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    @foreach ($users as $user)
                        <div
                            class="flex flex-col rounded-[28px] overflow-hidden shadow-lg transition-transform duration-300 ease-out hover:scale-[1.02] bg-white">
                            <!-- Header de la card con color del sistema -->
                            <div class="bg-[#372C97] text-white flex items-center justify-center px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 md:w-14 md:h-14 border-[1px] border-[#F7DE0C] rounded-full flex items-center justify-center bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="#372C97" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="w-8 h-8 md:w-10 md:h-10 object-contain">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl md:text-2xl font-semibold">{{ $user->name }}</span>
                                </div>
                            </div>

                            <!-- Contenido de la card -->
                            <div class="flex flex-1 flex-col bg-[#F7DE0C] px-6 py-5">
                                <div class="space-y-4">
                                    <!-- Email -->
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-700 mb-1">Correo electrónico</span>
                                        <span
                                            class="text-base md:text-lg text-[#1F1F1F] font-medium break-all">{{ $user->email }}</span>
                                    </div>

                                    <!-- Cédula -->
                                    @if ($user->cedula)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-700 mb-1">Cédula</span>
                                            <span
                                                class="text-base md:text-lg text-[#1F1F1F] font-medium">{{ $user->cedula }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-400 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay usuarios disponibles</h3>
                        <p class="text-gray-500">No se encontraron usuarios en el sistema (excluyendo superadmin).</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
