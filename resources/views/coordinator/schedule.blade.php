@extends('layouts.coordinator')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-extrabold text-[#3E3F29]">
            Schedule
        </h1>
        <p class="text-sm text-gray-600">
            Create and manage your event schedules.
        </p>
    </div>

    <!-- CALENDAR CARD -->
    <div class="bg-white rounded-2xl shadow p-4">
        <div id="calendar"></div>
    </div>

</div>

<!-- ================= ADD EVENT MODAL ================= -->
<div id="addEventModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-[#3E3F29] mb-4">
            Add Schedule
        </h3>

        <div class="space-y-4 text-sm">

            <input id="eventTitle" type="text"
                   placeholder="Event Name"
                   class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                          focus:ring-2 focus:ring-[#778873] focus:outline-none">

            <input id="eventDate" type="date"
                   class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                          focus:ring-2 focus:ring-[#778873] focus:outline-none">

            <input id="eventTime" type="time"
                   class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                          focus:ring-2 focus:ring-[#778873] focus:outline-none">

            <input id="eventLocation" type="text"
                   placeholder="Location"
                   class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                          focus:ring-2 focus:ring-[#778873] focus:outline-none">

            <select id="eventStatus"
                    class="w-full px-4 py-3 rounded-lg border border-[#A1BC98]
                           focus:ring-2 focus:ring-[#778873] focus:outline-none">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Completed">Completed</option>
            </select>

        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button onclick="closeAddModal()"
                    class="px-4 py-2 rounded-lg border">
                Cancel
            </button>
            <button onclick="saveEvent()"
                    class="px-5 py-2 rounded-lg bg-[#3E3F29] text-white">
                Save
            </button>
        </div>
    </div>
</div>

<!-- ================= VIEW EVENT MODAL ================= -->
<div id="viewEventModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-[#3E3F29] mb-4">
            Schedule Details
        </h3>

        <div class="space-y-2 text-sm text-gray-700">
            <p><strong>Event:</strong> <span id="viewTitle"></span></p>
            <p><strong>Date:</strong> <span id="viewDate"></span></p>
            <p><strong>Time:</strong> <span id="viewTime"></span></p>
            <p><strong>Location:</strong> <span id="viewLocation"></span></p>
            <p><strong>Status:</strong> <span id="viewStatus"></span></p>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="closeViewModal()"
                    class="px-4 py-2 rounded-lg bg-[#3E3F29] text-white">
                Close
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
let calendar
let selectedDate = null

document.addEventListener('DOMContentLoaded', function () {

    calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        height: 650,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        selectable: true,

        dateClick(info) {
            selectedDate = info.dateStr
            document.getElementById('eventDate').value = selectedDate
            openAddModal()
        },

        eventClick(info) {
            openViewModal(info.event)
        },

        events: []
    })

    calendar.render()
})

/* ===== ADD EVENT ===== */
function saveEvent() {
    const title = document.getElementById('eventTitle').value
    const date = document.getElementById('eventDate').value
    const time = document.getElementById('eventTime').value
    const location = document.getElementById('eventLocation').value
    const status = document.getElementById('eventStatus').value

    if (!title || !date || !time) return alert('Complete all required fields')

    calendar.addEvent({
        title,
        start: `${date}T${time}`,
        extendedProps: { location, status },
        color: status === 'Approved' ? '#778873' :
               status === 'Completed' ? '#3E3F29' : '#A1BC98'
    })

    closeAddModal()
    clearForm()
}

/* ===== VIEW EVENT ===== */
function openViewModal(event) {
    document.getElementById('viewTitle').textContent = event.title
    document.getElementById('viewDate').textContent =
        event.start.toLocaleDateString()
    document.getElementById('viewTime').textContent =
        event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
    document.getElementById('viewLocation').textContent =
        event.extendedProps.location
    document.getElementById('viewStatus').textContent =
        event.extendedProps.status

    document.getElementById('viewEventModal').classList.remove('hidden')
    document.getElementById('viewEventModal').classList.add('flex')
}

/* ===== MODALS ===== */
function openAddModal() {
    document.getElementById('addEventModal').classList.remove('hidden')
    document.getElementById('addEventModal').classList.add('flex')
}

function closeAddModal() {
    document.getElementById('addEventModal').classList.add('hidden')
    document.getElementById('addEventModal').classList.remove('flex')
}

function closeViewModal() {
    document.getElementById('viewEventModal').classList.add('hidden')
    document.getElementById('viewEventModal').classList.remove('flex')
}

function clearForm() {
    ['eventTitle','eventTime','eventLocation'].forEach(id =>
        document.getElementById(id).value = ''
    )
}
</script>
@endpush
