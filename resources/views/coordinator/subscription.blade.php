@extends('layouts.coordinator')

@section('content')

<div class="space-y-10">

    <!-- PAGE HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            Subscription
        </h1>
        <p class="text-sm text-gray-600">
            Manage your plan and billing information
        </p>
    </div>

    <!-- CURRENT PLAN -->
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <div>
            <p class="text-sm text-gray-500">Current Plan</p>
            <h2 class="text-2xl font-extrabold text-[#3E3F29]">
                Pro Coordinator
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Expires on <span class="font-semibold">January 15, 2026</span>
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('coordinator.checkout') }}"
               class="px-6 py-3 rounded-lg bg-[#3E3F29] text-white font-semibold hover:opacity-90 transition">
                Renew Plan
            </a>

            <button
                class="px-6 py-3 rounded-lg border border-[#3E3F29] text-[#3E3F29] font-semibold hover:bg-[#3E3F29] hover:text-white transition">
                Cancel Subscription
            </button>
        </div>

    </div>

    <!-- AVAILABLE PLANS -->
    <div>
        <h3 class="text-xl font-bold text-[#3E3F29] mb-4">
            Available Plans
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- BASIC -->
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h4 class="text-lg font-bold text-[#3E3F29]">Basic</h4>
                <p class="text-3xl font-extrabold mt-4">₱499</p>
                <p class="text-sm text-gray-500">per month</p>

                <ul class="mt-6 space-y-2 text-sm text-gray-600">
                    <li>Up to 10 bookings</li>
                    <li>Basic reports</li>
                    <li>No income tracking</li>
                </ul>

                <button class="w-full mt-6 py-3 rounded-lg bg-[#A1BC98] text-[#3E3F29] font-semibold hover:bg-[#778873] hover:text-white transition">
                    Choose Plan
                </button>
            </div>

            <!-- PRO (ACTIVE) -->
            <div class="bg-white rounded-2xl shadow p-6 border-2 border-[#3E3F29] relative">
                <span class="absolute -top-3 left-4 px-3 py-1 text-xs rounded-full bg-[#3E3F29] text-white">
                    Current Plan
                </span>

                <h4 class="text-lg font-bold text-[#3E3F29] mt-3">Pro Coordinator</h4>
                <p class="text-3xl font-extrabold mt-4">₱999</p>
                <p class="text-sm text-gray-500">per month</p>

                <ul class="mt-6 space-y-2 text-sm text-gray-600">
                    <li>Unlimited bookings</li>
                    <li>Income & payment tracking</li>
                    <li>Ratings & feedback</li>
                </ul>

                <button disabled
                    class="w-full mt-6 py-3 rounded-lg bg-gray-300 text-gray-600 font-semibold cursor-not-allowed">
                    Active
                </button>
            </div>

            <!-- ENTERPRISE -->
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h4 class="text-lg font-bold text-[#3E3F29]">Enterprise</h4>
                <p class="text-3xl font-extrabold mt-4">₱1,999</p>
                <p class="text-sm text-gray-500">per month</p>

                <ul class="mt-6 space-y-2 text-sm text-gray-600">
                    <li>Multi-coordinator access</li>
                    <li>Advanced analytics</li>
                    <li>Priority support</li>
                </ul>

                <button class="w-full mt-6 py-3 rounded-lg bg-[#A1BC98] text-[#3E3F29] font-semibold hover:bg-[#778873] hover:text-white transition">
                    Upgrade
                </button>
            </div>

        </div>
    </div>

    <!-- BILLING HISTORY -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <h3 class="text-lg font-bold text-[#3E3F29] px-6 py-4 border-b">
            Billing History
        </h3>

        <table class="w-full text-sm text-left">
            <thead class="bg-[#A1BC98]/40 text-[#3E3F29]">
                <tr>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Plan</th>
                    <th class="px-5 py-3">Amount</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr>
                    <td class="px-5 py-3">Dec 15, 2025</td>
                    <td class="px-5 py-3">Pro Coordinator</td>
                    <td class="px-5 py-3 font-semibold">₱999</td>
                    <td class="px-5 py-3">
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            Paid
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="px-5 py-3">Nov 15, 2025</td>
                    <td class="px-5 py-3">Pro Coordinator</td>
                    <td class="px-5 py-3 font-semibold">₱999</td>
                    <td class="px-5 py-3">
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            Paid
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection
