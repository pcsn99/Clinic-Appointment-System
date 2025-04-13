@extends('layouts.app')

@section('content')
<h2>My Appointments</h2>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color: red">{{ session('error') }}</p>
@endif

<a href="{{ route('dashboard') }}">
    <button class="btn btn-secondary mb-3">Back to Dashboard</button>
</a>

@if($existingBooking)
    <div class="alert alert-info">
        You already have a booking on <strong>{{ $existingBooking->schedule->date }}</strong> 
        ({{ $existingBooking->schedule->start_time }} - {{ $existingBooking->schedule->end_time }}).
        You must cancel it to book another.
    </div>
@endif

{{-- FullCalendar --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<div style="display: flex; gap: 20px;">
    <div style="flex: 1;">
        <div id="calendar"></div>
    </div>
    <div style="flex: 1;">
        <h5>Select a date in the calendar</h5>
        <div id="slotDetails"></div>
    </div>
</div>

{{-- Booking Modal --}}
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('student.appointments.book') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="bookingModalDetails">Loading...</p>
                    <input type="hidden" name="schedule_id" id="scheduleIdInput">
                    <div id="pinPromptSection" style="display: none;">
                        <label>Enter PIN to override full slot:</label>
                        <input type="text" name="pin" class="form-control mt-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($existingBooking)
    <hr>
    <h4>Your Current Booking</h4>
    <table class="table table-bordered" style="max-width: 600px;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Present</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $existingBooking->schedule->date }}</td>
                <td>{{ $existingBooking->schedule->start_time }} - {{ $existingBooking->schedule->end_time }}</td>
                <td>
                    @if($existingBooking->status === 'booked')
                        <span class="badge bg-warning text-dark">Booked</span>
                    @elseif($existingBooking->status === 'completed')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>
                <td>{{ $existingBooking->is_present ? 'Yes' : 'No' }}</td>
                <td>
                    @if($existingBooking->status === 'booked' && !$existingBooking->is_present)
                        <form method="POST" action="{{ route('student.appointments.cancel', $existingBooking->id) }}" onsubmit="return confirm('Cancel your appointment?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                        </form>
                    @else
                        <em>N/A</em>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const slotDetails = document.getElementById('slotDetails');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            validRange: {
                start: new Date().toISOString().split('T')[0] // Prevent past date clicks
            },
            events: '/appointments/calendar-events',
            dateClick: function (info) {
                const date = info.dateStr;
                slotDetails.innerHTML = `<strong>Loading slots for ${date}...</strong>`;

                fetch(`/appointments/schedules-by-date?date=${date}`)
                    .then(res => res.json())
                    .then(slots => {
                        if (!slots.length) {
                            slotDetails.innerHTML = `<p>No slots available for this day.</p>`;
                            return;
                        }

                        let html = '<table class="table table-sm"><thead><tr><th>Time</th><th>Action</th></tr></thead><tbody>';

                        slots.forEach(slot => {
                            const full = slot.booked >= slot.slot_limit;
                            const startTime = slot.start_time;
                            const endTime = slot.end_time;

                            html += `
                                <tr title="Click to Book">
                                    <td>${startTime} - ${endTime} (${slot.booked}/${slot.slot_limit})</td>
                                    <td>
                                        <button class="btn btn-sm ${full ? 'btn-warning' : 'btn-primary'}"
                                            onclick="openBookingModal(${slot.id}, '${startTime}', '${endTime}', ${full})">
                                            ${full ? 'Override with PIN' : 'Book Schedule'}
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        html += '</tbody></table>';
                        slotDetails.innerHTML = html;
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
