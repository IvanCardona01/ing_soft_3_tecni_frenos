@extends('layouts.app')

@section('title', 'login')

@section('styles')
    <link rel="stylesheet" href="css/login.css">
@endsection
@section('content')

    <div class="container d-flex flex-column align-items-center justify-content-start">

        <div class="logo-container mt-[165px] mb-4">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo">
        </div>

        <div class="container2 d-flex flex-column align-items-center w-100">

            <h2 class="text-center mt-3 fw-bold">Iniciar Sesión</h2>

            <form id="forms" class="d-flex flex-column align-items-center needs-validation mt-3" novalidate
                style="width: 100%;">
                <!-- Usuario -->
                <div class="mb-4 w-100 d-flex flex-column align-items-center position-relative" style="max-width: 600px;">
                    <label for="username" class="form-label w-100 text-start text-xl fw-bold">Usuario</label>
                    <input type="text" class="form-control p-3 h-100 rounded-pill border opacity-75 mb-2" id="username"
                        placeholder="Ingresa tu cédula" style="height: 40px; width: 600px;" required>
                    <div class="invalid-feedback t-20">Por favor, ingresa tu usuario.</div>

                </div>

                <!-- Contraseña -->
                <div class="mb-3 w-100 d-flex flex-column align-items-center position-relative" style="max-width: 600px;">
                    <label for="password" class="form-label w-100 text-start text-xl fw-bold">Contraseña</label>
                    <input type="password" class="form-control p-3 h-100 rounded-pill border opacity-75 pe-5 mb-2"
                        id="password" placeholder="Ingresa tu contraseña" style="height: 40px; width: 600px;" required>
                    <div class="invalid-feedback">Por favor, ingresa tu contraseña.</div>

                    <!-- Icono del ojo dentro del input -->
                    <i class="bi bi-eye position-absolute top-70" id="togglePassword"
                        style="right: 30px; top: 60px; transform: translateY(-50%); font-size: 1.2rem; cursor: pointer; opacity: 0.7;">
                    </i>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary fw-bold mt-4"
                    style="background-color: #372C97; color:#F7DE0C; width: 300px; height: 55px; border-radius: 1000px;">
                    Continuar
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
