@extends('layouts.client')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Payment Status & History</h1>
        <p class="text-gray-600">Review all payments for your bookings</p>
    </div>

    {{-- SUMMARY CARDS --}}
    @if(isset($totalAmount))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Booked Amount</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">₱{{ number_format($totalAmount, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Amount Paid</div>
            <div class="mt-2 text-3xl font-bold text-green-600">₱{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Remaining Balance</div>
            <div class="mt-2 text-3xl font-bold {{ $totalBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
                ₱{{ number_format($totalBalance, 2) }}
            </div>
        </div>
    </div>
    @endif

    {{-- BOOKINGS TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if(isset($bookings) && count($bookings) > 0)
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Coordinator</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paid</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Balance</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="toggleDetails({{ $booking->id }})">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $booking->event_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->coordinator->user->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₱{{ number_format($booking->total_paid, 2) }}</td>
                    <td class="px-6 py-4 text-sm {{ $booking->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }} font-semibold">
                        ₱{{ number_format($booking->remaining_balance, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $booking->payment_percentage }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">{{ $booking->payment_percentage }}%</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               $booking->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                </tr>

                {{-- PAYMENT DETAILS ROW (HIDDEN) --}}
                <tr id="details-{{ $booking->id }}" class="hidden bg-gray-50">
                    <td colspan="7" class="px-6 py-4">
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900">Payment History</h4>
                            @if($booking->payments->count() > 0)
                            <div class="space-y-2">
                                @foreach($booking->payments as $payment)
                                <div class="flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $payment->date_paid->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">
                                            {{ ucfirst($payment->method) }}
                                            @if($payment->notes)
                                            - {{ $payment->notes }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-sm text-yellow-800">No payments recorded yet</p>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500">No bookings with payment information</p>
        </div>
        @endif
    </div>

    {{-- PAGINATION --}}
    @if(isset($bookings))
    <div class="mt-6">
        {{-- Add pagination if paginated --}}
    </div>
    @endif
</div>

<script>
    function toggleDetails(bookingId) {
        const row = document.getElementById(`details-${bookingId}`);
        row.classList.toggle('hidden');
    }
</script>
@endsection
