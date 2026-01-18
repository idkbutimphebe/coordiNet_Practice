<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reports dashboard (graphs + summary)
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * 8.1 & 8.2 - Top coordinators + list of coordinators
     */
    public function coordinators()
    {
        return view('reports.coordinators');
    }

    /**
     * 8.3 - List of clients
     */
    public function clients()
    {
        return view('reports.clients');
    }

    /**
     * 8.4 - List of bookings
     */
    public function bookings()
    {
        return view('reports.bookings');
    }

    /**
     * 8.5 - Income report per coordinator
     */
    public function income()
    {
        return view('reports.income');
    }

    /**
     * 8.6 - Client ratings and feedback
     */
    public function ratings()
    {
        return view('reports.ratings');
    }
}
