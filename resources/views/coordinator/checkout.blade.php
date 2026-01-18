@extends('layouts.coordinator')

@section('content')

<div class="max-w-3xl mx-auto space-y-8">

    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            Checkout
        </h1>
        <p class="text-sm text-gray-600">
            Complete your subscription payment
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 space-y-6">

        <div class="flex justify-between text-sm">
            <span>Plan</span>
            <span class="font-semibold">Pro Coordinator</span>
        </div>

        <div class="flex justify-between text-sm">
            <span>Billing Cycle</span>
            <span class="font-semibold">Monthly</span>
        </div>

        <div class="flex justify-between text-lg font-bold">
            <span>Total</span>
            <span>₱999</span>
        </div>

        <form method="POST" action="{{ route('coordinator.pay') }}">
            @csrf

            <button
                class="w-full py-4 rounded-xl
                       bg-gradient-to-r from-[#778873] to-[#3E3F29]
                       text-white font-semibold hover:opacity-90">
                Pay with PayPal
            </button>
        </form>

    </div>

</div>

@endsection
