<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    public function index()
    {
        return view('coordinators.index');
    }

    public function byEvent($event)
    {
        return view('coordinators.list', compact('event'));
    }

    public function show($event, $id)
    {
        return view('coordinators.show', compact('event', 'id'));
    }
}
