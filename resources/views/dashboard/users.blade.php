@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Gestionar Usuarios', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="dashboard-fondo">
        <div class="container-content-main mt-20 md:mt-32 px-4 md:p-8" style="background-color: #ffffff91; height: 100%;">
            <div class="flex flex-col gap-2">
                <div class="my-4 self-center text-2xl md:text-3xl lg:text-4xl font-bold text-[#372C97]">Gestión de
                    Usuarios</div>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex justify-end md:ml-auto">
                    <a href="{{ route('dashboard.users.create') }}"
                        class="flex items-center justify-center gap-2 bg-[#372C97] text-white px-4 md:px-6 py-3 rounded-xl font-semibold text-base md:text-lg hover:bg-[#2a1f7a] transition-colors shadow-md hover:shadow-lg w-full md:w-auto no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Crear nuevo usuario</span>
                        <span class="sm:hidden">Crear usuario</span>
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('dashboard.users') }}" id="filters-form"
                class="flex flex-col md:flex-row w-full justify-between items-stretch md:items-center gap-4 mb-8">
                <div class="w-full md:max-w-[300px]">
                    <div class="relative">
                        <input type="text" name="email" id="email" value="{{ $filters['email'] ?? '' }}"
                            placeholder="Buscar por correo electrónico"
                            class="w-full border-2 border-indigo-700 rounded-xl py-3 pl-4 pr-12 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <img src="{{ asset('icons/searcher-icon.svg') }}" alt="Buscar" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <select id="role-filter" name="role"
                    class="h-[50px] border-2 border-indigo-700 rounded-xl px-4 pr-12 w-full md:max-w-[250px]
           appearance-none bg-no-repeat bg-right-4 bg-center bg-contain
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                    style="background-size: 20px; background-position: right 12px center; background-image: url('/icons/arrow-down.svg');">
                    <option value="todos" {{ ($filters['role'] ?? 'todos') === 'todos' ? 'selected' : '' }}>Todos los roles
                    </option>
                    <option value="admin" {{ ($filters['role'] ?? '') === 'admin' ? 'selected' : '' }}>Admin
                    </option>
                    <option value="mechanic" {{ ($filters['role'] ?? '') === 'mechanic' ? 'selected' : '' }}>Mecánico
                    </option>
                </select>
            </form>

            @if ($users->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mt-4 md:mt-8">
                    @foreach ($users as $user)
                        <div
                            class="flex flex-col rounded-[28px] overflow-hidden shadow-lg transition-transform duration-300 ease-out hover:scale-[1.02] bg-white">
                            <!-- Header de la card con color del sistema -->
                            <div class="bg-[#372C97] text-white flex items-center justify-center px-4 py-3 md:px-6 md:py-4">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div
                                        class="w-10 h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 border-[1px] border-[#F7DE0C] rounded-full flex items-center justify-center bg-white flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="#372C97" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10 object-contain">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-lg md:text-xl lg:text-2xl font-semibold truncate">{{ $user->name }}</span>
                                </div>
                            </div>

                            <!-- Contenido de la card -->
                            <div class="flex flex-1 flex-col bg-[#F7DE0C] px-4 py-4 md:px-6 md:py-5">
                                <div class="space-y-3 md:space-y-4">
                                    <!-- Email -->
                                    <div class="flex flex-col">
                                        <span class="text-xs md:text-sm font-semibold text-gray-700 mb-1">Correo
                                            electrónico</span>
                                        <span
                                            class="text-sm md:text-base lg:text-lg text-[#1F1F1F] font-medium break-all">{{ $user->email }}</span>
                                    </div>

                                    <!-- Cédula -->
                                    @if ($user->cedula)
                                        <div class="flex flex-col">
                                            <span class="text-xs md:text-sm font-semibold text-gray-700 mb-1">Cédula</span>
                                            <span
                                                class="text-sm md:text-base lg:text-lg text-[#1F1F1F] font-medium">{{ $user->cedula }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 px-4 bg-white rounded-[30px] shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-400 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No se encontraron usuarios</h3>
                    <p class="text-gray-500 text-center">
                        @if (($filters['role'] ?? 'todos') !== 'todos' || !empty($filters['email'] ?? ''))
                            No hay usuarios que coincidan con los filtros aplicados.
                        @else
                            No se encontraron usuarios en el sistema (excluyendo superadmin).
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filters-form');
            const roleFilter = document.getElementById('role-filter');
            const emailInput = document.getElementById('email');

            // Aplicar filtros automáticamente cuando cambia el rol
            if (roleFilter) {
                roleFilter.addEventListener('change', () => {
                    form.submit();
                });
            }

            // Para la búsqueda por email, buscar solo cuando el usuario termine (blur) o presione Enter
            if (emailInput) {
                // Buscar cuando el input pierde el foco
                emailInput.addEventListener('blur', () => {
                    form.submit();
                });

                // Permitir búsqueda inmediata al presionar Enter
                emailInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        form.submit();
                    }
                });
            }
        });
    </script>
@endsection
