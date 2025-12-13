<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
