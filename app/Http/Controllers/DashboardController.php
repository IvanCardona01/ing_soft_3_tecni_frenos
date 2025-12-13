<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function index()
    {
        return view('dashboard.index');
    }
    public function orderService()
    {
        return view('dashboard.orderService');
    }
    public function orders()
    {
        return view('dashboard.orders');
    }

    public function users(Request $request)
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->with('roles');

        // Filtro por rol
        if ($request->filled('role') && $request->role !== 'todos') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Búsqueda por correo electrónico
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->get();

        $filters = [
            'role' => $request->role ?? 'todos',
            'email' => $request->email ?? '',
        ];

        return view('dashboard.users', compact('users', 'filters'));
    }

    /**
     * Muestra el formulario de creación de usuarios.
     */
    public function createUser()
    {
        return view('dashboard.create-user');
    }

    /**
     * Guarda un nuevo usuario en el sistema.
     */
    public function storeUser(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cedula' => $validated['cedula'],
        ];

        // Solo guardar contraseña si el rol es admin
        if ($validated['role'] === 'admin' && !empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        } else {
            // Para mecánicos, generar una contraseña aleatoria que no se usará
            // (ya que no tienen acceso al sistema)
            $userData['password'] = Hash::make(uniqid('mechanic_', true));
        }

        $user = User::create($userData);

        // Asignar el rol al usuario
        $user->assignRole($validated['role']);

        $roleName = $validated['role'] === 'admin' ? 'Admin' : 'Mecánico';
        return redirect()
            ->route('dashboard.users')
            ->with('status', "Usuario {$user->name} creado exitosamente con rol {$roleName}");
    }
}
