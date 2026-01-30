<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Dashboard (summary + graphs)
    public function index()
    {
        return view('reports.index');
    }

    // Top 10 coordinators
    public function topCoordinators()
    {
        return view('reports.topcoordinators');
    }

    // List of coordinators
    public function coordinators()
    {
        return view('reports.coordinators');
    }

    // List of clients
    public function clients()
    {
        return view('reports.clients');
    }

    // List of bookings
    public function bookings()
    {
        return view('reports.bookings');
    }

    // Income report per coordinator
    public function income()
    {
        return view('reports.income');
    }

    // Client ratings and feedback
    public function ratings()
    {
        return view('reports.ratings');
    }
}
