<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout()
    {
        return view('coordinator.checkout');
    }

    public function pay(Request $request)
    {
        //
    }
}
