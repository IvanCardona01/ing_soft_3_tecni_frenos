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

    public function users()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'superadmin');
        })->with('roles')->get();

        return view('dashboard.users', compact('users'));
    }
}
