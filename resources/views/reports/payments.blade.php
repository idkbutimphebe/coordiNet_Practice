@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Payment Ledger</h1>
        <p class="text-gray-600">View all payments across all coordinators</p>
    </div>

    {{-- STATISTICS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Collected</div>
            <div class="mt-2 text-3xl font-bold text-green-600">₱{{ number_format($totalCollected, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Payments</div>
            <div class="mt-2 text-3xl font-bold text-blue-600">{{ number_format($totalPayments) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Average Payment</div>
            <div class="mt-2 text-3xl font-bold text-indigo-600">
                ₱{{ number_format($totalPayments > 0 ? $totalCollected / $totalPayments : 0, 2) }}
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('reports.payments') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Search --}}
                <input type="text" name="search" placeholder="Search coordinator or client..."
                       value="{{ request('search') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">

                {{-- Coordinator Filter --}}
                <select name="coordinator_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Coordinators</option>
                    @foreach($allCoordinators as $coordinator)
                        <option value="{{ $coordinator->id }}" {{ request('coordinator_id') == $coordinator->id ? 'selected' : '' }}>
                            {{ $coordinator->coordinator_name }}
                        </option>
                    @endforeach
                </select>

                {{-- Payment Method --}}
                <select name="method" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="gcash" {{ request('method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>Check</option>
                    <option value="other" {{ request('method') == 'other' ? 'selected' : '' }}>Other</option>
                </select>

                {{-- Date Range --}}
                <div class="flex gap-2">
                    <input type="date" name="date_from" placeholder="From" value="{{ request('date_from') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <input type="date" name="date_to" placeholder="To" value="{{ request('date_to') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                {{-- Search Button --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- PAYMENTS TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Payment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Coordinator</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date Paid</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono">#{{ $payment->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <a href="#" class="text-blue-600 hover:underline">
                                {{ $payment->booking->coordinator->coordinator_name ?? 'Unknown' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $payment->booking->client->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $payment->booking->event_name }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $payment->method === 'cash' ? 'bg-green-100 text-green-800' :
                                   $payment->method === 'gcash' ? 'bg-blue-100 text-blue-800' :
                                   $payment->method === 'bank' ? 'bg-purple-100 text-purple-800' :
                                   'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($payment->method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->date_paid->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <p class="text-sm">No payments found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection
