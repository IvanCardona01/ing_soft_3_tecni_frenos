<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard.index');
    }
    public function orderService() {
        return view('dashboard.orderService');
    }
    public function orders() {
        return view('dashboard.orders');
    }
}
