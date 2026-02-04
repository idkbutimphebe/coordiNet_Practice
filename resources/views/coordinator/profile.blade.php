@extends('layouts.coordinator')

@section('content')

@if(session('success'))
    <div class="max-w-7xl mx-auto mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="max-w-7xl mx-auto mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('coordinator.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header Buttons --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#3E3F29]">Edit Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your public profile details.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold hover:bg-gray-50 text-sm">Cancel</a>
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#3E3F29] text-white font-semibold hover:bg-[#2c2d1e] shadow-md transition-all text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                {{-- Personal Info & Avatar --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-8 items-start">
                    
                    <div class="relative group shrink-0 mx-auto sm:mx-0">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-[#A1BC98] relative bg-gray-100 shadow-md">
                            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=A1BC98&color=3E3F29' }}" 
                                 id="avatar-preview"
                                 class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-[2px]">
                                <span class="text-[10px] text-white font-bold uppercase tracking-wider">Change</span>
                            </div>
                        </div>
                        <input type="file" name="avatar" onchange="previewImage(event, 'avatar-preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        @error('avatar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex-1 w-full space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 text-[#3E3F29] font-bold focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Job Title</label>
                                <input type="text" name="title" value="{{ old('title', $user->title) }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 text-gray-600 font-medium focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 text-gray-600 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 text-gray-600 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Location</label>
                            <input type="text" name="location" value="{{ old('location', $user->location) }}"
                                   class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 text-gray-600 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                        </div>
                    </div>
                </div>

                {{-- Bio --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-[#3E3F29] mb-4 text-lg">Expertise & Description</h2>
                    <textarea rows="4" name="bio" class="w-full px-4 py-3 rounded-xl bg-[#F9FAFB] border border-gray-200 text-gray-600 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none resize-none">{{ old('bio', $user->bio) }}</textarea>
                </div>

                {{-- Services (FIXED) --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-[#3E3F29] mb-4 text-lg">Services Offered</h2>
                    @php
                        $availableServices = ['Full Planning', 'On-the-day Coord', 'Vendor Management', 'Budget Planning', 'Styling', 'Hosting', 'Catering', 'Photography'];
                        // FIX: Check old() first, then database
                        $dbServices = is_string($user->services) ? json_decode($user->services, true) : ($user->services ?? []);
                        $currentServices = old('services', $dbServices) ?? [];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        @foreach($availableServices as $service)
                            <label class="cursor-pointer relative">
                                <input type="checkbox" name="services[]" value="{{ $service }}" 
                                       class="peer sr-only"
                                       {{ in_array($service, $currentServices) ? 'checked' : '' }}>
                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 bg-white border-gray-200 text-gray-500 hover:bg-gray-50 peer-checked:bg-[#F6F8F5] peer-checked:border-[#A1BC98] peer-checked:text-[#3E3F29] peer-checked:font-bold">
                                    <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center peer-checked:border-[#778873] peer-checked:bg-[#778873] transition-colors">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    {{ $service }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

 {{-- Event Types (Fixed List) --}}
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <h2 class="font-bold text-[#3E3F29] mb-4 text-lg">Event Types You Handle</h2>

    @php
        // Available event types
        $availableEvents = ['Wedding', 'Birthday', 'Debut', 'Corporate', 'Anniversary', 'Baby Shower', 'Graduation', 'Engagement'];

        // Get existing event types from user profile (JSON column)
        $dbEvents = is_array($user->event_types ?? null) ? ($user->event_types ?? []) : [];

        // Merge old input with database values
        $currentEvents = old('event_types', $dbEvents) ?? [];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        @foreach($availableEvents as $event)
            <label class="cursor-pointer relative">
                <input type="checkbox" name="event_types[]" value="{{ $event }}"
                       class="peer sr-only"
                       {{ in_array($event, $currentEvents) ? 'checked' : '' }}>
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200 bg-white border-gray-200 text-gray-500 hover:bg-gray-50 peer-checked:bg-[#F6F8F5] peer-checked:border-[#A1BC98] peer-checked:text-[#3E3F29] peer-checked:font-bold">
                    <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center peer-checked:border-[#778873] peer-checked:bg-[#778873] transition-colors">
                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    {{ $event }}
                </div>
            </label>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-2">Select the event types you handle. You can check multiple options.</p>
    @error('event_types')
        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>



                {{-- Portfolio (FIXED) --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-[#3E3F29] mb-4 text-lg">Portfolio</h2>
                    @php 
                        // Normalize portfolio data
                        $dbPortfolio = is_string($user->portfolio) ? json_decode($user->portfolio, true) : ($user->portfolio ?? []);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @for($i = 1; $i <= 3; $i++)
                        @php
                            // Get image and desc safely, preferring old input if validation failed
                            $imgSrc = $dbPortfolio[$i]['image'] ?? null;
                            $descVal = old("portfolio.$i.desc", $dbPortfolio[$i]['desc'] ?? '');
                        @endphp
                        <div class="group relative rounded-xl border-2 border-dashed border-gray-300 hover:border-[#A1BC98] hover:bg-[#F6F8F5] transition-all p-4 flex flex-col items-center text-center">
                            <div class="w-full aspect-video bg-gray-100 rounded-lg mb-3 overflow-hidden relative">
                                <img src="{{ $imgSrc ? asset('storage/'.$imgSrc) : '' }}" 
                                     id="portfolio-preview-{{$i}}" 
                                     class="w-full h-full object-cover {{ $imgSrc ? '' : 'hidden' }}">
                                
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 {{ $imgSrc ? 'hidden' : '' }}" id="portfolio-placeholder-{{$i}}">
                                    <span class="text-xs">Select Image</span>
                                </div>
                            </div>
                            
                            {{-- FIX: Using Array Syntax portfolio[1][image] --}}
                            <input type="file" name="portfolio[{{$i}}][image]" 
                                   onchange="previewImage(event, 'portfolio-preview-{{$i}}'); document.getElementById('portfolio-placeholder-{{$i}}').classList.add('hidden'); document.getElementById('portfolio-preview-{{$i}}').classList.remove('hidden');" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                   
                            <input type="text" name="portfolio[{{$i}}][desc]" 
                                   value="{{ $descVal }}" 
                                   placeholder="Event Description..." 
                                   class="w-full text-xs border-b border-gray-200 bg-transparent py-1 text-[#3E3F29] focus:border-[#A1BC98] outline-none z-20 relative">
                        </div>
                        @endfor
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                {{-- Rate --}}
                <div class="bg-[#3E3F29] text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-[#778873] rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-32 h-32 bg-[#A1BC98] rounded-full blur-3xl opacity-30"></div>
                    <h3 class="font-bold text-lg mb-2 relative z-10">Starting Rate</h3>
                    <div class="relative z-10 mt-3 mb-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-lg">₱</span>
                        <input type="number" name="rate" value="{{ old('rate', $user->rate) }}" class="w-full pl-10 pr-4 py-4 rounded-xl bg-white text-[#3E3F29] font-extrabold text-3xl focus:ring-4 focus:ring-[#A1BC98]/50 outline-none">
                    </div>
                    <p class="text-xs text-right text-gray-400 mt-2 relative z-10">per event</p>
                </div>

                {{-- Security --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-[#3E3F29] mb-4 text-lg">Account Security</h2>
                    <div class="space-y-4">
                        <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100 mb-2">
                            <p class="text-xs text-yellow-700">Leave these blank if you do not want to change your password.</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">New Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 focus:bg-white focus:ring-2 focus:ring-[#A1BC98] outline-none">
                        </div>
                    </div>
                </div>

                {{-- Is Active (FIXED) --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                     <label class="relative flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-bold text-[#3E3F29]">Accepting New Clients</span>
                        {{-- Hidden input handles the "Unchecked" state --}}
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[22px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#778873]"></div>
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Toggle off to hide profile.</p>
                </div>

            </div>
        </div>
    </div>
</form>

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