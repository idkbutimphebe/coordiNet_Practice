@extends('layouts.coordinator')

@section('content')

<div class="space-y-10">

    <!-- ================= PAGE HEADER ================= -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            My Profile
        </h1>
        <p class="text-sm text-gray-600">
            Manage your profile, services, portfolio, and subscription features
        </p>
    </div>

    <!-- ================= PROFILE HEADER ================= -->
    <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-6">

        <div class="w-20 h-20 rounded-full bg-[#A1BC98] flex items-center justify-center
                    text-[#3E3F29] text-2xl font-bold">
            CN
        </div>

        <div>
            <h2 class="text-xl font-bold text-[#3E3F29]">
                Coordinator Name
            </h2>
            <p class="text-sm text-gray-600">
                coordinator@email.com
            </p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full
                         bg-[#A1BC98]/40 text-[#3E3F29] text-xs font-semibold">
                Premium Subscriber
            </span>
        </div>
    </div>

    <!-- ================= PERSONAL INFORMATION ================= -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
            Personal Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Full Name</label>
                <input type="text" value="Coordinator Name"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                              focus:ring-2 focus:ring-[#778873] focus:outline-none">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Email Address</label>
                <input type="email" value="coordinator@email.com"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                              focus:ring-2 focus:ring-[#778873] focus:outline-none">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Phone Number</label>
                <input type="text" value="+63 912 345 6789"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                              focus:ring-2 focus:ring-[#778873] focus:outline-none">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Location</label>
                <input type="text" value="Cebu City, Philippines"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                              focus:ring-2 focus:ring-[#778873] focus:outline-none">
            </div>

        </div>
    </div>

    <!-- ================= EXPERTISE & BIO ================= -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
            Expertise & Description
        </h3>

        <textarea rows="4"
                  placeholder="Describe your experience, specialization, and expertise..."
                  class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                         focus:ring-2 focus:ring-[#778873] focus:outline-none">
Professional event coordinator specializing in weddings, birthdays, and corporate events.
        </textarea>
    </div>

    <!-- ================= SERVICES & PRICING ================= -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
            Services & Pricing
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">Service Name</label>
                <input type="text" placeholder="Wedding Coordination"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Price (₱)</label>
                <input type="number" placeholder="15000"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Availability</label>
                <select
                    class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]">
                    <option>Available</option>
                    <option>Fully Booked</option>
                </select>
            </div>

        </div>
    </div>

    <!-- ================= PORTFOLIO UPLOAD ================= -->
<div class="bg-white rounded-2xl shadow p-6">
    <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
        Portfolio (Premium Feature)
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- PORTFOLIO ITEM 1 -->
        <div class="border border-[#A1BC98] rounded-xl p-4">

            <div class="h-40 rounded-lg bg-[#A1BC98]/20
                        flex items-center justify-center text-[#778873]">
                <span class="text-sm">Image Preview</span>
            </div>

            <input type="file"
                   class="mt-4 w-full text-sm text-gray-500">

            <textarea
                rows="2"
                placeholder="Enter description..."
                class="mt-3 w-full rounded-lg border border-[#A1BC98]
                       px-3 py-2 text-sm
                       focus:outline-none focus:ring-2
                       focus:ring-[#778873]">
            </textarea>

        </div>

        <!-- PORTFOLIO ITEM 2 -->
        <div class="border border-[#A1BC98] rounded-xl p-4">

            <div class="h-40 rounded-lg bg-[#A1BC98]/20
                        flex items-center justify-center text-[#778873]">
                <span class="text-sm">Image Preview</span>
            </div>

            <input type="file"
                   class="mt-4 w-full text-sm text-gray-500">

            <textarea
                rows="2"
                placeholder="Enter description..."
                class="mt-3 w-full rounded-lg border border-[#A1BC98]
                       px-3 py-2 text-sm
                       focus:outline-none focus:ring-2
                       focus:ring-[#778873]">
            </textarea>

        </div>

        <!-- PORTFOLIO ITEM 3 -->
        <div class="border border-[#A1BC98] rounded-xl p-4">

            <div class="h-40 rounded-lg bg-[#A1BC98]/20
                        flex items-center justify-center text-[#778873]">
                <span class="text-sm">Image Preview</span>
            </div>

            <input type="file"
                   class="mt-4 w-full text-sm text-gray-500">

            <textarea
                rows="2"
                placeholder="Enter description..."
                class="mt-3 w-full rounded-lg border border-[#A1BC98]
                       px-3 py-2 text-sm
                       focus:outline-none focus:ring-2
                       focus:ring-[#778873]">
            </textarea>

        </div>

    </div>
</div>


    <!-- ================= ACCOUNT SECURITY ================= -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
            Account Security
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm font-medium text-gray-600">New Password</label>
                <input type="password"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Confirm Password</label>
                <input type="password"
                       class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98]">
            </div>

        </div>
    </div>

    <!-- ================= SAVE BUTTON ================= -->
    <div class="flex justify-end">
        <button
            class="px-8 py-3 rounded-lg bg-[#3E3F29] text-white font-semibold hover:opacity-90 transition">
            Save Changes
        </button>
    </div>

</div>

@endsection
