@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Crear Usuario', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
    <div class="dashboard-fondo">
        <div class="container-content-main mt-24 md:mt-32 px-4 md:p-8" style="background-color: #ffffff91; height: 100%;">
            <div class="mb-[-40px]">
                <div class="flex flex-col gap-2 mt-4">
                    <div class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#372C97] self-center">Crear Nuevo Usuario
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.users.store') }}" class="space-y-8">
                @csrf

                <div class="data-client">
                    <span>Datos del usuario</span>
                    <div class="form-client-data">
                        <div class="form-group">
                            <label for="role">Rol*</label>
                            <select id="role" name="role" class="w-full" required>
                                <option value="">Seleccione un rol</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                                <option value="mechanic" @selected(old('role') === 'mechanic')>Mecánico</option>
                            </select>
                            @error('role')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre Completo*</label>
                            <input type="text" id="name" name="name" placeholder="Digita nombre completo"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico*</label>
                            <input type="email" id="email" name="email" placeholder="Digita correo electrónico"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cedula">Cédula*</label>
                            <input type="text" id="cedula" name="cedula" placeholder="Digita número de cédula"
                                value="{{ old('cedula') }}" required>
                            @error('cedula')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group" id="password-group">
                            <label for="password">Contraseña*</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" placeholder="Digita contraseña"
                                    style="padding-right: 2.75rem; width: 100%; box-sizing: border-box;">
                                <i class="bi bi-eye" id="togglePassword" role="button" tabindex="0"
                                    aria-label="Mostrar contraseña"
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 1.2rem; cursor: pointer; opacity: 0.7;"></i>
                            </div>
                            <small class="text-gray-600 mt-1 block">
                                La contraseña debe tener mínimo 8 caracteres, incluir al menos una letra, un número y un
                                carácter especial.
                            </small>
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group" id="password-confirmation-group">
                            <label for="password_confirmation">Confirmar Contraseña*</label>
                            <div style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Confirma la contraseña"
                                    style="padding-right: 2.75rem; width: 100%; box-sizing: border-box;">
                                <i class="bi bi-eye" id="togglePasswordConfirm" role="button" tabindex="0"
                                    aria-label="Mostrar contraseña"
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 1.2rem; cursor: pointer; opacity: 0.7;"></i>
                            </div>
                            @error('password_confirmation')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="data-client container-buttons">
                    <div class="flex flex-col md:flex-row gap-3 items-center justify-between w-full">
                        <a href="{{ route('dashboard.users') }}"
                            class="px-20 py-3 fw-bold text-center no-underline w-full md:w-auto bg-white text-[#372C97] border-2 border-[#372C97] hover:bg-[#372C97] hover:text-white transition-colors shadow-md hover:shadow-lg"
                            style="border-radius: 1000px;">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold w-full md:w-auto"
                            style="border-radius: 1000px;">
                            Crear Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const passwordGroup = document.getElementById('password-group');
            const passwordConfirmationGroup = document.getElementById('password-confirmation-group');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            function togglePasswordFields() {
                const selectedRole = roleSelect.value;

                if (selectedRole === 'mechanic') {
                    // Ocultar campos de contraseña para mecánicos
                    passwordGroup.style.display = 'none';
                    passwordConfirmationGroup.style.display = 'none';
                    passwordInput.removeAttribute('required');
                    passwordConfirmationInput.removeAttribute('required');
                    passwordInput.value = '';
                    passwordConfirmationInput.value = '';
                    passwordInput.type = 'password';
                    passwordConfirmationInput.type = 'password';
                    const t1 = document.getElementById('togglePassword');
                    const t2 = document.getElementById('togglePasswordConfirm');
                    if (t1) {
                        t1.classList.remove('bi-eye-slash');
                        t1.classList.add('bi-eye');
                        t1.setAttribute('aria-label', 'Mostrar contraseña');
                    }
                    if (t2) {
                        t2.classList.remove('bi-eye-slash');
                        t2.classList.add('bi-eye');
                        t2.setAttribute('aria-label', 'Mostrar contraseña');
                    }
                } else {
                    // Mostrar campos de contraseña para admin
                    passwordGroup.style.display = 'block';
                    passwordConfirmationGroup.style.display = 'block';
                    passwordInput.setAttribute('required', 'required');
                    passwordConfirmationInput.setAttribute('required', 'required');
                }
            }

            // Ejecutar al cargar la página (por si hay un valor old)
            togglePasswordFields();

            // Ejecutar cuando cambie el rol
            roleSelect.addEventListener('change', togglePasswordFields);

            function wirePasswordToggle(toggleEl, inputEl) {
                if (!toggleEl || !inputEl) {
                    return;
                }
                function onToggle() {
                    if (inputEl.type === 'password') {
                        inputEl.type = 'text';
                        toggleEl.classList.replace('bi-eye', 'bi-eye-slash');
                        toggleEl.setAttribute('aria-label', 'Ocultar contraseña');
                    } else {
                        inputEl.type = 'password';
                        toggleEl.classList.replace('bi-eye-slash', 'bi-eye');
                        toggleEl.setAttribute('aria-label', 'Mostrar contraseña');
                    }
                }
                toggleEl.addEventListener('click', onToggle);
                toggleEl.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        onToggle();
                    }
                });
            }

            wirePasswordToggle(document.getElementById('togglePassword'), passwordInput);
            wirePasswordToggle(document.getElementById('togglePasswordConfirm'), passwordConfirmationInput);
        });
    </script>
@endsection
