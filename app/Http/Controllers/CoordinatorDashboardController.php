<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorDashboardController extends Controller
{
    public function index()
    {
        return view('coordinator.dashboard');
    }
}
