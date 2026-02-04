@php
    $client = Auth::user()->client;
@endphp

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

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="p-4 mb-6 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGES -->
    @if($errors->any())
        <div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT PROFILE SUMMARY -->
            <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center text-center">
                <div class="relative group w-28 h-28">
                    <div class="w-full h-full rounded-full overflow-hidden border-4 border-[#A1BC98] bg-gray-100 flex items-center justify-center text-4xl font-extrabold text-[#3E3F29]">
                        <img id="avatar-preview" 
                             src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=A1BC98&color=3E3F29' }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-full">
                            <span class="text-[10px] text-white font-bold uppercase tracking-wider">Change</span>
                        </div>
                    </div>
                    <input type="file" name="avatar" onchange="previewImage(event, 'avatar-preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer rounded-full">
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

            <!-- RIGHT PROFILE FORM -->
            <div class="lg:col-span-2 space-y-8">

                <!-- PERSONAL INFORMATION -->
                <div class="bg-white rounded-2xl shadow p-8">
                    <h3 class="text-lg font-bold text-[#3E3F29] mb-6">
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', optional($client)->phone_number) }}" placeholder="+63 9XX XXX XXXX" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Address</label>
                            <input type="text" name="address" value="{{ old('address', optional($client)->address) }}" placeholder="City, Philippines" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
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
                            <label class="text-sm font-medium text-gray-600">New Password</label>
                            <input type="password" name="password" placeholder="********" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="********" class="mt-1 w-full px-4 py-3 rounded-lg border border-[#A1BC98] focus:ring-2 focus:ring-[#778873] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- SAVE BUTTON -->
                <div class="flex justify-end">
                    <button type="submit" class="px-10 py-3 rounded-xl bg-gradient-to-r from-[#778873] to-[#3E3F29] text-white font-semibold hover:opacity-90 transition">
                        Save Changes
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>

<script>
    function previewImage(event, targetId) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById(targetId);
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

@endsection
