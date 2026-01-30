@extends('layouts.dashboard')

@section('content')

<form action="YOUR_UPDATE_ROUTE_HERE" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="max-w-7xl mx-auto space-y-6 mt-8">

        <div class="flex items-center justify-between px-2">
            <div>
                <h1 class="text-3xl font-extrabold text-[#3E3F29]">
                    Edit Profile
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Manage coordinator details, services, and schedule
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold 
                          hover:bg-gray-50 transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit" 
                        class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#3E3F29] text-white font-semibold 
                               hover:bg-[#2c2d1e] shadow-md hover:shadow-lg transition-all duration-300 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-6 items-start">
                    
                    <div class="relative group shrink-0 mx-auto sm:mx-0">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#A1BC98] relative bg-gray-100">
                            <img src="{{ asset('image/premium_photo-1661374927471-24a90ebd5737.jpg') }}" 
                                 id="avatar-preview"
                                 class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/50 flex items-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <input type="file" name="avatar" onchange="previewImage(event)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <p class="text-[10px] text-center text-gray-400 mt-2 uppercase tracking-wide font-bold">Change Photo</p>
                    </div>

                    <div class="flex-1 w-full space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Full Name</label>
                                <input type="text" name="name" value="Juan Dela Cruz"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 
                                              text-[#3E3F29] font-bold focus:bg-white focus:ring-2 focus:ring-[#A1BC98] focus:border-transparent outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#778873] uppercase tracking-wider ml-1">Job Title</label>
                                <input type="text" name="title" value="Professional Event Coordinator"
                                       class="w-full px-4 py-2.5 rounded-xl bg-[#F9FAFB] border border-gray-200 
                                              text-gray-600 font-medium focus:bg-white focus:ring-2 focus:ring-[#A1BC98] focus:border-transparent outline-none transition-all">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:border-gray-300 after:border after:rounded-full 
                                            after:h-5 after:w-5 after:transition-all peer-checked:bg-[#778873]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-500 group-hover:text-[#3E3F29] transition-colors">
                                    Set as Active Coordinator
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-[#3E3F29] mb-4">Services Offered</h2>

                    @php
                        $availableServices = [
                            'Full Event Planning', 'Vendor Coordination', 'Timeline Management', 
                            'On-site Supervision', 'Budget Planning', 'Post-event Support', 
                            'Venue Selection', 'Catering Management'
                        ];
                        $currentServices = [
                            'Full Event Planning', 'Vendor Coordination', 'Timeline Management', 
                            'On-site Supervision', 'Budget Planning', 'Post-event Support'
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        @foreach($availableServices as $service)
                            <label class="cursor-pointer relative">
                                <input type="checkbox" name="services[]" value="{{ $service }}" 
                                       class="peer sr-only"
                                       {{ in_array($service, $currentServices) ? 'checked' : '' }}>
                                
                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border transition-all duration-200
                                            bg-white border-gray-200 text-gray-500 hover:bg-gray-50
                                            peer-checked:bg-[#F6F8F5] peer-checked:border-[#A1BC98] peer-checked:text-[#3E3F29] peer-checked:font-semibold">
                                    
                                    <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center
                                                peer-checked:border-[#778873] peer-checked:bg-[#778873]">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    {{ $service }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-[#3E3F29]">Availability</h3>
                        <button type="button" class="text-xs font-bold text-[#778873] hover:text-[#3E3F29] border border-gray-200 hover:border-[#3E3F29] px-3 py-1 rounded-lg transition-colors">
                            Manage Schedule
                        </button>
                    </div>
        
                    <div class="grid grid-cols-7 gap-2 text-center text-xs">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                            <div class="text-gray-400 font-medium">{{ $day }}</div>
                        @endforeach
        
                        @for($i = 1; $i <= 30; $i++)
                            @php
                                $isBooked = in_array($i, [6, 12, 18, 24]); 
                            @endphp
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg mx-auto
                                {{ $isBooked 
                                    ? 'bg-red-50 text-red-400 border border-red-100 cursor-not-allowed' 
                                    : 'bg-[#A1BC98]/20 text-[#3E3F29] hover:bg-[#A1BC98] hover:text-white cursor-pointer transition-colors' 
                                }}">
                                {{ $i }}
                            </div>
                        @endfor
                    </div>
        
                    <div class="flex gap-4 mt-5 pt-4 border-t border-gray-100 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-[#A1BC98] rounded-sm"></span> Open
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-red-300 rounded-sm"></span> Booked
                        </span>
                    </div>
                </div>

                <div class="bg-[#3E3F29] text-white rounded-2xl p-6 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-[#778873] rounded-full blur-2xl opacity-50"></div>
                    
                    <h3 class="font-semibold mb-2 relative z-10">Base Rate</h3>
                    
                    <div class="relative z-10 mt-3 mb-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₱</span>
                        <input type="number" name="rate" value="5000"
                               class="w-full pl-9 pr-4 py-3 rounded-xl bg-white text-[#3E3F29] font-bold text-2xl
                                      focus:ring-4 focus:ring-[#A1BC98]/50 outline-none transition shadow-inner">
                    </div>
                    <p class="text-xs text-gray-300 mb-4 relative z-10">per event</p>
                </div>

            </div>
        </div>

    </div>
</form>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection