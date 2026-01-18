@extends('layouts.client')

@section('content')

<div class="space-y-10 w-full">

    <!-- ================= PAGE HEADER ================= -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#3E3F29]">
                My Profile
            </h1>
            <p class="text-sm text-gray-600">
                Manage your personal information and account security
            </p>
        </div>
    </div>

    <!-- ================= PROFILE GRID ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- ================= LEFT PROFILE SUMMARY ================= -->
        <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center text-center">

            <!-- AVATAR -->
            <div class="w-28 h-28 rounded-full bg-[#A1BC98]
                        flex items-center justify-center
                        text-[#3E3F29] text-4xl font-extrabold uppercase">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>

            <h2 class="mt-4 text-xl font-bold text-[#3E3F29]">
                {{ Auth::user()->name }}
            </h2>

            <p class="text-sm text-gray-600">
                {{ Auth::user()->email }}
            </p>

            <div class="mt-6 w-full border-t pt-6 space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Role</span>
                    <span class="font-semibold text-[#3E3F29]">Client</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Status</span>
                    <span class="font-semibold text-green-600">Active</span>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT PROFILE FORM ================= -->
        <div class="lg:col-span-2 space-y-8">

            <!-- PERSONAL INFORMATION -->
            <div class="bg-white rounded-2xl shadow p-8">
                <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
                    Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            Full Name
                        </label>
                        <input
                            type="text"
                            value="{{ Auth::user()->name }}"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            Email Address
                        </label>
                        <input
                            type="email"
                            value="{{ Auth::user()->email }}"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            placeholder="+63 9XX XXX XXXX"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            Address
                        </label>
                        <input
                            type="text"
                            placeholder="City, Philippines"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                </div>
            </div>

            <!-- ACCOUNT SECURITY -->
            <div class="bg-white rounded-2xl shadow p-8">
                <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
                    Account Security
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            New Password
                        </label>
                        <input
                            type="password"
                            placeholder="********"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            placeholder="********"
                            class="mt-1 w-full px-4 py-3 rounded-lg
                                   border border-[#A1BC98]
                                   focus:ring-2 focus:ring-[#778873]
                                   focus:outline-none">
                    </div>

                </div>
            </div>

            <!-- SAVE BUTTON -->
            <div class="flex justify-end">
                <button
                    class="px-10 py-3 rounded-xl
                           bg-gradient-to-r from-[#778873] to-[#3E3F29]
                           text-white font-semibold
                           hover:opacity-90 transition">
                    Save Changes
                </button>
            </div>

        </div>
    </div>

</div>

@endsection
