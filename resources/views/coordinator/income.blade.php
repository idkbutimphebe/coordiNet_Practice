@extends('layouts.coordinator')

@section('content')

<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#2F3024] tracking-tight">
                Income & Payments
            </h1>
            <p class="text-sm text-[#2F3024]/70">
                Track revenue and record manual payments.
            </p>
        </div>
        
        <button onclick="document.getElementById('recordPaymentModal').classList.remove('hidden')" 
            class="group flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#3E3F29] text-white font-bold shadow-lg hover:bg-[#556644] transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Record New Payment
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
        <div class="rounded-3xl p-8 bg-[#3E3F29] relative overflow-hidden shadow-xl col-span-1 md:col-span-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-white/70 font-bold">Total Collected</p>
                </div>
                {{-- Dynamic Total Income --}}
                <p class="mt-1 text-4xl font-black text-white">
                    ₱{{ number_format($totalIncome ?? 0) }}
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
                <div class="group relative overflow-hidden flex items-center justify-between rounded-2xl p-4 bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-[#A1BC98]/5 hover:-translate-y-0.5 transition-all duration-300">
                    {{-- Hover Side Glow --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#A1BC98] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#F6F8F5] to-[#E8EEE6] flex items-center justify-center text-[#3E3F29] text-sm font-black border border-gray-100 group-hover:scale-110 transition-transform">
                            {{ substr($payment->booking->client->name ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-[#2F3024] text-sm tracking-tight leading-none mb-1.5">
                                {{ $payment->booking->client->name ?? 'Unknown Client' }}
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter bg-gray-50 px-1.5 py-0.5 rounded">#{{ $payment->booking->id }}</span>
                                <span class="text-[9px] font-black text-[#A1BC98] uppercase">{{ $payment->booking->event_type ?? 'Event' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="font-black text-lg text-[#3E3F29] tracking-tighter leading-none">
                            <span class="text-xs font-medium text-gray-300 mr-0.5">₱</span>{{ number_format($payment->amount) }}
                        </p>
                        <div class="flex items-center justify-end gap-2 mt-1.5">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}</span>
                            <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded bg-[#3E3F29] text-white tracking-widest">Post</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50/50 rounded-2xl py-12 text-center border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">No transaction history</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
{{-- =============================================== --}}
{{--             RECORD PAYMENT MODAL                --}}
{{-- =============================================== --}}

<div id="recordPaymentModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg relative transform transition-transform scale-100">
        
        <button type="button" onclick="closeModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <h2 class="text-2xl font-black text-[#3E3F29] mb-1">Record Payment</h2>
        <p class="text-sm text-gray-500 mb-6">Enter payment details received from client.</p>

        <form action="{{ route('coordinator.payments.store') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- 1. CLIENT SELECTION --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Select Client</label>
                <div class="relative">
                    <select id="bookingSelect" name="booking_id" onchange="updateBalanceDisplay()" required
                            class="w-full p-3 pr-10 rounded-xl bg-[#F6F8F5] border-none focus:ring-2 focus:ring-[#A1BC98] text-[#3E3F29] font-semibold appearance-none">
                        <option value="" data-balance="0" data-total="0">Select a pending booking...</option>
                        
                        {{-- DYNAMIC LIST OF BOOKINGS FROM CONTROLLER --}}
                        @foreach($pendingBookings as $booking)
                            <option value="{{ $booking->id }}" 
                                    data-balance="{{ $booking->balance }}" 
                                    data-total="{{ $booking->total_price }}">
                                {{ $booking->client->name ?? 'Client' }} — {{ $booking->event_type }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Dropdown Arrow Icon --}}
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            {{-- 2. DYNAMIC BALANCE BOX (Hidden until selected) --}}
            <div id="balanceInfo" class="hidden bg-[#3E3F29] text-white rounded-xl p-4 flex justify-between items-center shadow-md animate-fade-in-down">
                <div>
                    <p class="text-[10px] uppercase text-[#A1BC98] font-bold tracking-wider">Remaining Balance</p>
                    <p class="text-xs text-gray-300">Total Contract: <span id="totalPriceDisplay" class="font-mono">0</span></p>
                </div>
                <p class="text-2xl font-black text-white" id="balanceDisplay">₱0.00</p>
            </div>

            {{-- 3. AMOUNT AND DATE --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Amount (₱)</label>
                    <input type="number" name="amount" step="0.01" placeholder="0.00" required
                           class="w-full p-3 rounded-xl bg-[#F6F8F5] border-none focus:ring-2 focus:ring-[#A1BC98] font-mono text-[#3E3F29]">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Date Received</label>
                    <input type="date" name="date_paid" value="{{ date('Y-m-d') }}" required
                           class="w-full p-3 rounded-xl bg-[#F6F8F5] border-none focus:ring-2 focus:ring-[#A1BC98]">
                </div>
            </div>

            {{-- 4. METHOD --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Payment Method</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="method" value="cash" class="peer hidden" checked>
                        <div class="p-2 text-center text-sm rounded-lg border border-gray-200 text-gray-600 peer-checked:bg-[#3E3F29] peer-checked:text-white peer-checked:border-[#3E3F29] transition-all font-medium">Cash</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="method" value="gcash" class="peer hidden">
                        <div class="p-2 text-center text-sm rounded-lg border border-gray-200 text-gray-600 peer-checked:bg-[#3E3F29] peer-checked:text-white peer-checked:border-[#3E3F29] transition-all font-medium">GCash</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="method" value="bank" class="peer hidden">
                        <div class="p-2 text-center text-sm rounded-lg border border-gray-200 text-gray-600 peer-checked:bg-[#3E3F29] peer-checked:text-white peer-checked:border-[#3E3F29] transition-all font-medium">Bank</div>
                    </label>
                </div>
            </div>

            {{-- 5. NOTES --}}
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Notes</label>
                <textarea name="notes" rows="2" placeholder="Reference Number or remarks..." class="w-full p-3 rounded-xl bg-[#F6F8F5] border-none focus:ring-2 focus:ring-[#A1BC98] text-sm"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-[#3E3F29] text-white font-bold hover:bg-[#556644] transition-all shadow-lg hover:shadow-xl translate-y-0 hover:-translate-y-0.5">
                Confirm Payment
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT TO HANDLE BALANCE DISPLAY --}}
<script>
    function updateBalanceDisplay() {
        const select = document.getElementById('bookingSelect');
        const selectedOption = select.options[select.selectedIndex];
        
        const balance = selectedOption.getAttribute('data-balance');
        const total = selectedOption.getAttribute('data-total');
        
        const infoBox = document.getElementById('balanceInfo');
        const balanceText = document.getElementById('balanceDisplay');
        const totalText = document.getElementById('totalPriceDisplay');

        if (balance && select.value !== "") {
            infoBox.classList.remove('hidden');
            
            // Format to Peso Currency
            let formatter = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' });

            balanceText.innerText = formatter.format(balance);
            totalText.innerText = formatter.format(total);
        } else {
            infoBox.classList.add('hidden');
        }
    }

    function closeModal() {
        document.getElementById('recordPaymentModal').classList.add('hidden');
        // Reset form (optional)
        // document.querySelector('form').reset();
        // document.getElementById('balanceInfo').classList.add('hidden');
    }
</script>

<style>
    /* Simple animation for the balance box */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
</style>
@endsection