@extends('layouts.coordinator')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* --- General Font --- */
    .fc { font-family: 'Inter', system-ui, sans-serif; }

    /* --- Toolbar --- */
    .fc .fc-toolbar-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #3E3F29;
    }

    .fc .fc-button-primary {
        background-color: white;
        border: 1px solid #E5E7EB;
        color: #3E3F29;
        font-weight: 600;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .fc .fc-button-primary:hover {
        background-color: #F3F4F6;
        border-color: #D1D5DB;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #3E3F29;
        border-color: #3E3F29;
        color: white;
    }

    /* --- Day Grid --- */
    .fc-theme-standard th { border: none; padding-bottom: 10px; }
    .fc .fc-col-header-cell-cushion { color: #9CA3AF; text-transform: uppercase; font-size: 0.75rem; font-weight: 700; }
    .fc-theme-standard td, .fc-theme-standard .fc-scrollgrid { border-color: #F3F4F6; }

    /* --- Date Numbers --- */
    .fc .fc-daygrid-day-number {
        color: #4B5563;
        font-weight: 600;
        padding: 8px 12px;
        z-index: 2; /* Keep number on top of background color */
        position: relative;
    }

    /* --- Today Highlight --- */
    .fc .fc-day-today .fc-daygrid-day-number {
        background-color: #3E3F29;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 4px;
    }

    /* --- Events (The Bars) --- */
    .fc-event {
        border: none !important;
        border-radius: 4px;
        padding: 2px 5px;
        margin-top: 2px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 3px rgba(0,0,0,0.1);
    }
</style>

<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            Schedule
        </h1>
        <p class="text-sm text-gray-600">
            Create and manage your event schedules.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-4">
        <div id="calendar"></div>
    </div>
</div>

<div id="addEventModal"
     class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
        
        <div class="bg-[#3E3F29] px-6 py-5 flex justify-between items-center relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div>
                <h3 class="text-xl font-bold text-white tracking-wide">Add New Schedule</h3>
                <p class="text-[#D3D8C8] text-xs mt-1">Fill in the details below</p>
            </div>
            <button onclick="closeAddModal()" class="text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2 z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Event Name <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input id="inputName" type="text" placeholder="e.g. Client Consultation"
                           class="w-full px-4 py-3 pl-4 rounded-xl bg-gray-50 border-gray-200 border focus:bg-white focus:border-[#3E3F29] focus:ring-1 focus:ring-[#3E3F29] transition-all outline-none text-gray-700 font-medium placeholder-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Date <span class="text-red-500">*</span></label>
                <input id="inputDate" type="date"
                       class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 border focus:bg-white focus:border-[#3E3F29] focus:ring-1 focus:ring-[#3E3F29] transition-all outline-none text-gray-700 font-medium">
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Start Time <span class="text-red-500">*</span></label>
                    <input id="inputStartTime" type="time"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 border focus:bg-white focus:border-[#3E3F29] focus:ring-1 focus:ring-[#3E3F29] transition-all outline-none text-gray-700 font-medium">
                </div>
                <div class="w-1/2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">End Time <span class="text-red-500">*</span></label>
                    <input id="inputEndTime" type="time"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 border focus:bg-white focus:border-[#3E3F29] focus:ring-1 focus:ring-[#3E3F29] transition-all outline-none text-gray-700 font-medium">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Location</label>
                <div class="relative">
                    <input id="inputLocation" type="text" placeholder="e.g. Conference Room A or Online"
                           class="w-full px-4 py-3 pl-4 rounded-xl bg-gray-50 border-gray-200 border focus:bg-white focus:border-[#3E3F29] focus:ring-1 focus:ring-[#3E3F29] transition-all outline-none text-gray-700 font-medium placeholder-gray-400">
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-5 flex justify-end gap-3 border-t border-gray-100">
            <button onclick="closeAddModal()" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold hover:bg-gray-100 transition-colors">
                Cancel
            </button>
            <button onclick="saveEvent()" class="px-8 py-2.5 rounded-xl bg-[#3E3F29] text-white font-bold hover:bg-[#2c2d1d] shadow-lg shadow-[#3E3F29]/30 transition-all transform hover:-translate-y-0.5">
                Save Schedule
            </button>
        </div>
    </div>
</div>

<div id="viewEventModal"
     class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-all duration-300">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100 border border-white/20">
        <div class="bg-[#3E3F29] p-8 text-center relative overflow-hidden">
             <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <button onclick="closeViewModal()" class="absolute top-4 right-4 text-white/60 hover:text-white transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 id="viewName" class="text-2xl font-extrabold text-white leading-tight mb-2 relative z-10">Event Name</h3>
            <p id="viewDateDisplay" class="text-[#D3D8C8] text-sm font-medium uppercase tracking-wide mt-4 relative z-10"></p>
        </div>

        <div class="p-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm">
                    <svg class="w-5 h-5 text-[#3E3F29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="viewTimeDisplay" class="text-lg font-bold text-[#3E3F29]">-- : --</span>
                </div>
            </div>
            <div class="flex justify-between items-center border-t border-gray-100 pt-5">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Location</span>
                </div>
                <span id="viewLocation" class="text-sm font-bold text-gray-800 text-right truncate max-w-[150px]"></span>
            </div>
        </div>
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <button onclick="closeViewModal()" class="text-sm font-bold text-gray-500 hover:text-[#3E3F29] transition-colors">Close Details</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
let calendar;

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 700,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        selectable: true,
        events: "{{ route('coordinator.events') }}",

        dateClick: function(info) {
            document.getElementById('inputDate').value = info.dateStr;
            openAddModal();
        },

        eventClick: function(info) {
            openViewModal(info.event);
        },

        // --- NEW: THIS IS THE PART THAT COLORS THE WHOLE DAY ---
        eventDidMount: function(info) {
            // 1. Get the date of the event (YYYY-MM-DD)
            let dateStr = info.event.startStr.split('T')[0];
            
            // 2. Find the grid cell for that date
            // This selects the background box of the specific day
            let dayCell = document.querySelector(`.fc-day[data-date="${dateStr}"]`);
            
            // 3. If found, add a background color (Light Green)
            if (dayCell) {
                // This color matches your theme but is very light (#EBF2E6 is light olive)
                dayCell.style.backgroundColor = '#EFF3EA'; 
                // Optional: Make the day number slightly darker to be readable
                dayCell.style.transition = 'background-color 0.3s ease';
            }
        }
    });

    calendar.render();
});

/* ================= SAVE LOGIC ================= */
function saveEvent() {
    const nameVal = document.getElementById('inputName').value;
    const dateVal = document.getElementById('inputDate').value;
    const startVal = document.getElementById('inputStartTime').value;
    const endVal = document.getElementById('inputEndTime').value;
    const locationVal = document.getElementById('inputLocation').value;

    if (!nameVal || !dateVal || !startVal || !endVal) {
        alert('Please fill in Event Name, Date, Start Time, and End Time.');
        return;
    }

    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    fetch("{{ route('coordinator.schedule.save') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
            name: nameVal,          
            date: dateVal,          
            start_time: startVal,   
            end_time: endVal,       
            location: locationVal  
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            let msg = data.message || 'Unknown Error';
            if (data.errors) msg = Object.values(data.errors).flat().join('\n');
            throw new Error(msg);
        }
        return data;
    })
    .then(data => {
        if(data.success) {
            calendar.refetchEvents();
            closeAddModal();
            clearForm();
        } else {
            alert('Something went wrong.');
        }
    })
    .catch(error => {
        console.error('Save Error:', error);
        alert('Failed to save:\n' + error.message);
    });
}

function openViewModal(event) {
    const props = event.extendedProps;
    document.getElementById('viewName').textContent = event.title;
    
    const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
    const dateStr = event.start.toLocaleDateString('en-US', dateOptions);
    document.getElementById('viewDateDisplay').textContent = dateStr;

    const timeOptions = { hour: '2-digit', minute: '2-digit' };
    const startStr = event.start ? event.start.toLocaleTimeString('en-US', timeOptions) : '';
    const endStr = event.end ? event.end.toLocaleTimeString('en-US', timeOptions) : '';
    document.getElementById('viewTimeDisplay').textContent = startStr + ' - ' + endStr;

    const locEl = document.getElementById('viewLocation');
    const locText = props.location || 'No Location Set';
    locEl.textContent = locText;

    const modal = document.getElementById('viewEventModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openAddModal() {
    document.getElementById('addEventModal').classList.remove('hidden');
    document.getElementById('addEventModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addEventModal').classList.add('hidden');
    document.getElementById('addEventModal').classList.remove('flex');
}

function closeViewModal() {
    document.getElementById('viewEventModal').classList.add('hidden');
    document.getElementById('viewEventModal').classList.remove('flex');
}

function clearForm() {
    document.getElementById('inputName').value = '';
    document.getElementById('inputStartTime').value = '';
    document.getElementById('inputEndTime').value = '';
    document.getElementById('inputLocation').value = '';
}
</script>
@endpush