@extends('layouts.app')

@section('content')
<div class="container mt-5">
    {{-- Appointment Header (Centered) --}}
    <div class="card text-center p-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-4">Appointment</h2>

        @if(session('success'))
            <p class="alert alert-success fs-5">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="alert alert-danger fs-5">{{ session('error') }}</p>
        @endif
    </div>

    {{-- Main Layout: Calendar on Left, Booking on Right --}}
    <div class="row mt-4">
        {{-- Calendar Section (Left Side) --}}
        <div class="col-md-6">
            <div class="card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Select a Date in the Calendar</h4>
                <div id="calendar"></div>
            </div>
        </div>

        {{-- Current Booking Section (Right Side) --}}
        <div class="col-md-6">
            <div class="card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Current Booking</h4>
                @if($existingBooking)
                    <div class="appointment-details fs-4">
                        <p><strong>Date:</strong> {{ $existingBooking->schedule->date }}</p>
                        <p><strong>Time:</strong> {{ $existingBooking->schedule->start_time }} - {{ $existingBooking->schedule->end_time }}</p>
                        <p><strong>Status:</strong>  
                            <span class="badge 
                                @if($existingBooking->status === 'booked') bg-warning text-dark
                                @elseif($existingBooking->status === 'completed') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($existingBooking->status) }}
                            </span>
                        </p>
                        <p><strong>Present:</strong> {{ $existingBooking->is_present ? 'Yes' : 'No' }}</p>
                    </div>

                    @if($existingBooking->status === 'booked' && !$existingBooking->is_present)
                        <form method="POST" action="{{ route('student.appointments.cancel', $existingBooking->id) }}" onsubmit="return confirm('Cancel your appointment?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg">Cancel Appointment</button>
                        </form>
                    @endif
                @else
                    <p class="text-muted fs-5">No current booking available.</p>
                @endif
            </div>

            {{-- Time & Action (Now under Current Booking, appearing dynamically) --}}
            <div class="card text-center p-4 mt-3" style="border: 2px solid #17224D;" id="timeActionBox" hidden>
                <h4 class="fw-bold display-5">Time & Action</h4>
                <div id="timeActionDetails" class="fs-4"></div>
            </div>
        </div>
    </div>

    {{-- Booking Modal --}}
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('student.appointments.book') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #17224D; color: white;">
                        <h5 class="modal-title fw-bold display-5">Confirm Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body fs-4">
                        <p id="bookingModalDetails">Loading...</p>
                        <input type="hidden" name="schedule_id" id="scheduleIdInput">
                        <div id="pinPromptSection" class="mt-2" style="display: none;">
                            <label class="fw-bold">Enter PIN to Override Full Slot:</label>
                            <input type="text" name="pin" class="form-control mt-2">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Confirm</button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FullCalendar Script --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const timeActionBox = document.getElementById('timeActionBox');
        const timeActionDetails = document.getElementById('timeActionDetails');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            validRange: { start: new Date().toISOString().split('T')[0] },
            events: '/appointments/calendar-events',
            dateClick: function (info) {
                const date = info.dateStr;
                timeActionDetails.innerHTML = `<strong>Loading time & action for ${date}...</strong>`;
                timeActionBox.hidden = true;

                fetch(`/appointments/schedules-by-date?date=${date}`)
                    .then(res => res.json())
                    .then(slots => {
                        if (!slots.length) {
                            timeActionDetails.innerHTML = `<p class="text-muted fs-5">No slots available for this day.</p>`;
                            timeActionBox.hidden = false;
                            return;
                        }

                        let html = '<table class="table table-lg text-center mx-auto"><thead><tr><th>Time</th><th>Action</th></tr></thead><tbody>';
                        slots.forEach(slot => {
                            const full = slot.booked >= slot.slot_limit;
                            html += `
                                <tr>
                                    <td class="fs-4">${slot.start_time} - ${slot.end_time} (${slot.booked}/${slot.slot_limit})</td>
                                    <td>
                                        <button class="btn btn-lg ${full ? 'btn-warning' : 'btn-primary'}"
                                            onclick="openBookingModal(${slot.id}, '${slot.start_time}', '${slot.end_time}', ${full})">
                                            ${full ? 'Override with PIN' : 'Book Schedule'}
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        html += '</tbody></table>';
                        timeActionDetails.innerHTML = html;
                        timeActionBox.hidden = false;
                    });
            }
        });

        calendar.render();
    });

    function openBookingModal(scheduleId, start, end, isFull) {
        const modalDetails = document.getElementById('bookingModalDetails');
        const scheduleInput = document.getElementById('scheduleIdInput');
        const pinPrompt = document.getElementById('pinPromptSection');

        modalDetails.innerHTML = `
            <strong>Time Slot:</strong> ${start} - ${end}<br>
            ${isFull ? '<span class="text-danger">This slot is full. Enter a PIN to override.</span>' : ''}
        `;
        scheduleInput.value = scheduleId;
        pinPrompt.style.display = isFull ? 'block' : 'none';

        const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
        modal.show();
    }
</script>
@endsection