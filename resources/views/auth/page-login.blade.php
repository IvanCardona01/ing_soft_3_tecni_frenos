@extends('layouts.app')

@section('title', 'Welcome')

@section('styles')
    <link rel="stylesheet" href="css/login.css">
@endsection
@section('content')

<div class="container">

<div class="container2">
    
    <div class="logo-container">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo">
    </div>

    <h2 id="text" class="text-center">Iniciar Sesión</h2>

    <form id="forms" class="d-flex flex-column align-items-center needs-validation" novalidate style="width: 100%;">
        <!-- Usuario -->
        <div class="mb-3 w-100 d-flex flex-column align-items-center" style="max-width: 600px;">
            <label for="username" class="form-label w-100 text-start">Usuario</label>
            <input type="text" class="form-control p-2 rounded-pill border opacity-75" id="username"
                placeholder="Ingresa tu cédula"
                style="height: 40px; width: 600px;" required>
        </div>

        <!-- Contraseña -->
        <div class="mb-3 w-100 d-flex flex-column align-items-center position-relative" style="max-width: 600px;">
            <label for="password" class="form-label w-100 text-start">Contraseña</label>
            <input type="password" class="form-control p-2 rounded-pill border opacity-75 pe-5" id="password"
                placeholder="Ingresa tu contraseña"
                style="height: 40px; width: 600px;" required>
            <div class="invalid-feedback">Por favor, ingresa tu contraseña.</div>

            <!-- Icono del ojo dentro del input -->
            <i class="bi bi-eye position-absolute" id="togglePassword"
                style="right: 30px; top: 74%; transform: translateY(-50%); font-size: 1.2rem; cursor: pointer; opacity: 0.7;">
            </i>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn btn-primary fw-bold"
            style="background-color: #372C97; color:#F7DE0C; width: 200px; height: 50px; border-radius: 1000px;">
            continuar
        </button>
    </form>




</div>
</div>



</div>



@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
