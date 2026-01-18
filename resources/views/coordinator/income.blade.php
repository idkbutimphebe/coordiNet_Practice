@extends('layouts.coordinator')

@section('content')

<div class="space-y-12">

    <!-- PAGE HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#2F3024] tracking-tight">
            Income & Payments
        </h1>
        <p class="text-sm text-[#2F3024]/70">
            Overview of earnings and payment records
        </p>
    </div>

   <!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="rounded-3xl p-7 bg-[#3E3F29]">
        <p class="text-xs uppercase tracking-wide text-white/70">
            Total Income
        </p>
        <p class="mt-3 text-4xl font-black text-white">
            ₱245,500
        </p>
    </div>

    <div class="rounded-3xl p-7 bg-[#3E3F29]">
        <p class="text-xs uppercase tracking-wide text-white/70">
            This Month
        </p>
        <p class="mt-3 text-4xl font-black text-white">
            ₱42,000
        </p>
    </div>

    <div class="rounded-3xl p-7 bg-[#3E3F29]">
        <p class="text-xs uppercase tracking-wide text-white/70">
            Pending Payments
        </p>
        <p class="mt-3 text-4xl font-black text-white">
            ₱18,000
        </p>
    </div>

</div>


    <!-- PAYMENT HISTORY -->
    <div class="rounded-3xl bg-[#A1BC98] p-8">

        <h2 class="text-xl font-bold text-[#2F3024] mb-6">
            Payment History
        </h2>

        <div class="space-y-4">

            <!-- ITEM -->
            <div class="flex items-center justify-between rounded-2xl p-6 bg-[#8FAF8A]">
                <div>
                    <p class="font-semibold text-[#2F3024]">
                        Maria Santos
                    </p>
                    <p class="text-xs text-[#2F3024]/80">
                        Wedding Event • Dec 15, 2025
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-[#2F3024]">
                        ₱60,000
                    </p>
                    <span class="inline-block mt-1 px-4 py-1 text-xs font-semibold rounded-full bg-[#2F3024] text-[#A1BC98]">
                        Paid
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl p-6 bg-[#8FAF8A]">
                <div>
                    <p class="font-semibold text-[#2F3024]">
                        John Reyes
                    </p>
                    <p class="text-xs text-[#2F3024]/80">
                        Birthday Event • Dec 18, 2025
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-[#2F3024]">
                        ₱12,000
                    </p>
                    <span class="inline-block mt-1 px-4 py-1 text-xs font-semibold rounded-full bg-[#2F3024] text-[#F6F8F5]">
                        Pending
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl p-6 bg-[#8FAF8A]">
                <div>
                    <p class="font-semibold text-[#2F3024]">
                        Anna Cruz
                    </p>
                    <p class="text-xs text-[#2F3024]/80">
                        Meeting • Dec 5, 2025
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-[#2F3024]">
                        ₱8,500
                    </p>
                    <span class="inline-block mt-1 px-4 py-1 text-xs font-semibold rounded-full bg-[#2F3024] text-[#F6F8F5]">
                        Refunded
                    </span>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
