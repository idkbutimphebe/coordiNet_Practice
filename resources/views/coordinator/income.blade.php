@extends('layouts.coordinator')

@section('content')

<div class="space-y-8 p-6">

    {{-- =============================================== --}}
    {{--    ERROR ALERT (ADDED TO SHOW WHY SAVE FAILS)   --}}
    {{-- =============================================== --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm leading-5 font-bold text-red-800">
                        There were errors with your submission
                    </h3>
                    <div class="mt-2 text-sm leading-5 text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#2F3024] tracking-tight">
                Income & Payments
            </h1>
            <p class="text-sm text-[#2F3024]/70">
                Track revenue and manage client payment records.
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('coordinator.dashboard') }}"
               class="px-4 py-3 text-sm font-bold rounded-2xl border-2 border-[#A1BC98] text-[#3E3F29] hover:bg-[#E3EAD7] transition-all">
                ← Back
            </a>
            
            <button onclick="document.getElementById('recordPaymentModal').classList.remove('hidden')" 
                class="group flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#3E3F29] text-white font-bold shadow-lg hover:bg-[#556644] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Record New Payment
            </button>
        </div>
    </div>

    {{-- STATISTICS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Total Collected --}}
        <div class="rounded-3xl p-8 bg-[#3E3F29] relative overflow-hidden shadow-xl group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-white/70 font-bold">Total Collected</p>
                </div>
                <p class="mt-1 text-3xl font-black text-white">
                    ₱{{ number_format($stats['totalCollected'] ?? 0, 2) }}
                </p>
            </div>
        </div>

        {{-- This Month --}}
        <div class="rounded-3xl p-8 bg-[#A1BC98] relative overflow-hidden shadow-xl group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-white/70 font-bold">This Month</p>
                </div>
                <p class="mt-1 text-3xl font-black text-white">
                    ₱{{ number_format($stats['thisMonth'] ?? 0, 2) }}
                </p>
            </div>
        </div>

        {{-- Total Payments --}}
        <div class="rounded-3xl p-8 bg-[#556644] relative overflow-hidden shadow-xl group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-white/70 font-bold">Transactions</p>
                </div>
                <p class="mt-1 text-3xl font-black text-white">
                    {{ $stats['totalPayments'] ?? 0 }}
                </p>
            </div>
        </div>

        {{-- Average Payment --}}
        <div class="rounded-3xl p-8 bg-[#2F3024] relative overflow-hidden shadow-xl group hover:-translate-y-1 transition-transform duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="20" x2="12" y2="10"></line>
                            <line x1="18" y1="20" x2="18" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="16"></line>
                        </svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-white/70 font-bold">Avg Payment</p>
                </div>
                <p class="mt-1 text-3xl font-black text-white">
                    ₱{{ number_format($stats['averagePayment'] ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>

    {{-- LEDGER SECTION --}}
    <div class="space-y-4">
        <div class="flex items-center gap-4 px-2">
            <h2 class="text-[11px] font-black text-[#2F3024] uppercase tracking-[0.2em]">Transaction Ledger</h2>
            <div class="h-[1px] flex-1 bg-gradient-to-r from-gray-200 via-gray-100 to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 gap-3">
            @forelse($payments as $payment)
                <div class="group relative overflow-hidden flex items-start justify-between rounded-2xl p-4 bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-[#A1BC98]/5 hover:-translate-y-0.5 transition-all duration-300">
                    
                    {{-- Hover Side Glow --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#A1BC98] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-start gap-4">
                        {{-- Avatar / Initials --}}
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#F6F8F5] to-[#E8EEE6] flex items-center justify-center text-[#3E3F29] text-lg font-black border border-gray-100 group-hover:scale-105 transition-transform flex-shrink-0">
                            {{ substr($payment->booking->client->name ?? 'C', 0, 1) }}
                        </div>
                        
                        <div class="flex flex-col">
                            {{-- Client Name --}}
                            <p class="font-black text-[#2F3024] text-base tracking-tight leading-none mb-2">
                                {{ $payment->booking->client->name ?? 'Unknown Client' }}
                            </p>

                            {{-- Row 1: Event & Method --}}
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-[10px] font-black text-[#A1BC98] uppercase bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                    {{ $payment->booking->event->name ?? $payment->booking->event_name ?? 'Event' }}
                                </span>
                                <span class="flex items-center gap-1 text-[10px] font-bold text-gray-500 uppercase">
                                    @if($payment->method == 'cash') 💵
                                    @elseif($payment->method == 'gcash') 📱
                                    @elseif($payment->method == 'bank') 🏦
                                    @else 📝
                                    @endif
                                    {{ ucfirst($payment->method) }}
                                </span>
                            </div>

                            {{-- Row 2: Reference Number --}}
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span class="font-medium text-gray-400">Ref:</span>
                                <span class="font-mono font-bold text-[#3E3F29] bg-gray-100 px-1.5 rounded">
                                    {{ $payment->reference_number ?? $payment->ref_no ?? $payment->transaction_id ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Row 3: Notes --}}
                            @if(!empty($payment->notes))
                                <div class="mt-1 text-xs text-gray-600 bg-[#F6F8F5] p-2 rounded-lg border border-gray-100 max-w-sm">
                                    <span class="font-bold text-[#A1BC98] text-[10px] uppercase tracking-wider mr-1">Note:</span>
                                    <span class="italic">{{ $payment->notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Side: Amount & Date --}}
                    <div class="text-right flex-shrink-0 ml-2">
                        <p class="font-black text-xl text-[#3E3F29] tracking-tighter leading-none">
                            <span class="text-xs font-medium text-gray-300 mr-0.5">₱</span>{{ number_format($payment->amount, 2) }}
                        </p>
                        <div class="flex items-center justify-end gap-2 mt-1.5">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50/50 rounded-2xl py-12 text-center border-2 border-dashed border-gray-100">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-400 text-xs font-black uppercase tracking-widest">No transaction history yet</p>
                    <p class="text-gray-400 text-xs mt-1">Record your first payment to get started</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
        
        {{-- View Full Report Link --}}
        <div class="text-center pt-4">
             <a href="{{ route('coordinator.reports.income') }}" 
                class="inline-flex items-center gap-2 text-sm font-bold text-[#A1BC98] hover:text-[#556644] transition-colors">
                 View Full Income Report →
             </a>
        </div>
    </div>
</div>

{{-- =============================================== --}}
{{--          RECORD PAYMENT MODAL                   --}}
{{-- =============================================== --}}

<div id="recordPaymentModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-gradient-to-r from-[#3E3F29] to-[#556644] p-6 rounded-t-3xl z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-white">Record Payment</h3>
                    <p class="text-xs text-white/70 mt-1">Add a new payment transaction</p>
                </div>
                <button onclick="document.getElementById('recordPaymentModal').classList.add('hidden')" 
                    class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <form action="{{ route('coordinator.payments.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- 1. SELECT CLIENT/BOOKING --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Select Client & Event</label>
                <div class="relative">
                    <select name="booking_id" required
                        class="w-full p-3 pr-10 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-semibold appearance-none">
                        <option value="">-- Select Pending Booking --</option>
                        
                        @foreach($pendingBookings as $booking)
                            <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                                {{ $booking->client->name ?? 'Client' }} — {{ $booking->event->name ?? $booking->event_name ?? 'Event' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @if($pendingBookings->isEmpty())
                    <p class="text-[10px] font-bold text-red-400 mt-2 uppercase tracking-wide">⚠️ No confirmed bookings found.</p>
                @endif
            </div>

            {{-- 2. AMOUNT --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Amount (₱)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3E3F29] font-bold text-lg">₱</span>
                    <input type="number" name="amount" step="0.01" min="0.01" placeholder="0.00" required value="{{ old('amount') }}"
                        class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-bold text-lg">
                </div>
            </div>

            {{-- 3. DATE & METHOD ROW --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Date --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Date Paid</label>
                    <input type="date" name="date_paid" value="{{ old('date_paid', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                        class="w-full p-3 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-semibold text-sm">
                </div>
                
                {{-- Method --}}
                <div>
                     <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Method</label>
                     <select name="method" required
                        class="w-full p-3 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-semibold text-sm appearance-none">
                        <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="gcash" {{ old('method') == 'gcash' ? 'selected' : '' }}>📱 GCash</option>
                        <option value="bank" {{ old('method') == 'bank' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="check" {{ old('method') == 'check' ? 'selected' : '' }}>📝 Check</option>
                        <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>📋 Other</option>
                    </select>
                </div>
            </div>

            {{-- 4. REFERENCE NUMBER --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Reference / Transaction ID</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}" placeholder="e.g. GCash Ref No. 123456789"
                    class="w-full p-3 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-semibold text-sm placeholder-gray-400">
            </div>

            {{-- 5. NOTES --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="2" placeholder="Any additional details..."
                    class="w-full p-3 rounded-xl bg-[#F6F8F5] border-2 border-transparent focus:border-[#A1BC98] focus:ring-0 text-[#3E3F29] font-semibold text-sm placeholder-gray-400">{{ old('notes') }}</textarea>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="pt-4 flex gap-3">
                <button type="button" 
                    onclick="document.getElementById('recordPaymentModal').classList.add('hidden')"
                    class="flex-1 py-3 rounded-xl border-2 border-gray-200 text-gray-500 font-bold hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                    class="flex-[2] py-3 rounded-xl bg-[#3E3F29] text-white font-bold shadow-lg hover:bg-[#556644] transition-all transform hover:scale-[1.02]">
                    💾 Save Transaction
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Success/Error Toasts --}}
@if(session('success'))
    <div id="toast-success" class="fixed top-4 right-4 bg-[#3E3F29] text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 animate-fade-in-down">
        <div class="bg-green-500 rounded-full p-1">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Success</h4>
            <p class="text-xs text-gray-300">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div id="toast-error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 animate-fade-in-down">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <div>
            <h4 class="font-bold text-sm">Error</h4>
            <p class="text-xs text-white/80">{{ session('error') }}</p>
        </div>
    </div>
@endif

<script>
    // 1. Auto-hide messages
    setTimeout(() => {
        const toasts = document.querySelectorAll('[id^="toast-"]');
        toasts.forEach(t => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(-20px)';
            setTimeout(() => t.remove(), 500);
        });
    }, 4000);

    // 2. AUTO-REOPEN MODAL IF THERE ARE ERRORS
    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('recordPaymentModal').classList.remove('hidden');
        });
    @endif
</script>

@endsection