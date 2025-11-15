<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm(): \Illuminate\View\View
    {
        return view('auth.page-login');
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validate([
            'credential' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'credential.required' => 'Ingresa tu correo o cédula.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        $field = filter_var($credentials['credential'], FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';

        if (Auth::attempt([$field => $credentials['credential'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors([
                'credential' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ])
            ->onlyInput('credential');
    }

    /**
     * Cierra la sesión del usuario autenticado.
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

