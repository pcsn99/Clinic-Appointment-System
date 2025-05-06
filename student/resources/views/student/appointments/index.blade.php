@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card">
        <h2 class="text-primary fw-bold display-4">Appointment</h2>

        @if(session('success'))
            <p class="alert alert-success fs-5">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="alert alert-danger fs-5">{{ session('error') }}</p>
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg action-btn">🏠 Back to Dashboard</a>
        </div>
    </div>

    @if($existingBooking)
    <div class="card appointment-card">
        <h4 class="fw-bold text-info display-5">Current Booking</h4>
        <div class="appointment-details fs-4">
            <p><strong>Date:</strong> {{ $existingBooking->schedule->date }}</p>
            <p><strong>Time:</strong> {{ $existingBooking->schedule->start_time }} - {{ $existingBooking->schedule->end_time }}</p>
            <p><strong>Status:</strong> 
                @if($existingBooking->status === 'booked')
                    <span class="badge bg-warning text-dark">Booked</span>
                @elseif($existingBooking->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </p>
            <p><strong>Present:</strong> {{ $existingBooking->is_present ? 'Yes' : 'No' }}</p>
        </div>

        @if($existingBooking->status === 'booked' && !$existingBooking->is_present)
            <form method="POST" action="{{ route('student.appointments.cancel', $existingBooking->id) }}" onsubmit="return confirm('Cancel your appointment?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-lg action-btn">Cancel Appointment</button>
            </form>
        @endif
    </div>
    @endif

   
    <div class="card schedule-card">
        <h4 class="fw-bold display-5">Select a Date in the Calendar</h4>
        <div id="calendar"></div>
        <div id="slotDetails" class="mt-4 fs-4"></div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('student.appointments.book') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
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
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-lg action-btn">Confirm</button>
                        <button type="button" class="btn btn-secondary btn-lg action-btn" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FullCalendar Script -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const slotDetails = document.getElementById('slotDetails');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                validRange: {
                    start: new Date().toISOString().split('T')[0] 
                },
                events: '/appointments/calendar-events',
                dateClick: function (info) {
                    const date = info.dateStr;
                    slotDetails.innerHTML = `<strong>Loading slots for ${date}...</strong>`;

                    fetch(`/appointments/schedules-by-date?date=${date}`)
                        .then(res => res.json())
                        .then(slots => {
                            if (!slots.length) {
                                slotDetails.innerHTML = `<p class="fs-4 text-muted">No slots available for this day.</p>`;
                                return;
                            }

                            let html = '<table class="table table-lg"><thead><tr><th>Time</th><th>Action</th></tr></thead><tbody>';
                            slots.forEach(slot => {
                                const full = slot.booked >= slot.slot_limit;
                                const startTime = slot.start_time;
                                const endTime = slot.end_time;

                                html += `
                                    <tr>
                                        <td class="fs-4">${startTime} - ${endTime} (${slot.booked}/${slot.slot_limit})</td>
                                        <td>
                                            <button class="btn btn-lg ${full ? 'btn-warning' : 'btn-primary'}"
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
</div>

<style>
    .container {
        max-width: 1000px;
    }

    .card {
        border: none;
        border-radius: 12px;
        padding: 40px;
        margin-bottom: 30px;
        background-color: #f8f9fa;
    }

    .info-card {
        text-align: center;
        background-color: #ffffff;
        border: 2px solid #dcdcdc;
    }

    .appointment-card {
        background-color: #e9f5ff;
        border-left: 8px solid #17224D;
    }

    .schedule-card {
        background-color: #e7f3fe;
        border-left: 8px solid #17224D;
    }

    .fs-4, .fs-5 {
        font-size: 22px;
    }

    .action-btn {
        padding: 16px;
        font-size: 20px;
        font-weight: bold;
        border-radius: 12px;
    }
</style>

@endsection