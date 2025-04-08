@extends('layouts.app')

@section('content')
<h2>Upcoming Appointments</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


{{-- Calendar --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<div id="calendar" style="max-width: 900px; margin: 30px auto; border: 1px solid #ccc; padding: 10px;"></div>

{{-- Modal --}}
<div class="modal fade" id="slotModal" tabindex="-1" aria-labelledby="slotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="slotModalLabel">Schedules on <span id="modalDate"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="slotModalBody">Loading...</div>
    </div>
  </div>
</div>

<a href="{{ route('admin.appointments.create') }}">
    <button>➕ Create Appointment for Student</button>
</a>

<br><br>

<br>

<table id="appointmentsTable" border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Student</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Present</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appt)
            <tr>
                <td>{{ $appt->user->name }}</td>
                <td>{{ $appt->schedule->date }}</td>
                <td>{{ $appt->schedule->start_time }} - {{ $appt->schedule->end_time }}</td>
                <td>
                    @if($appt->status === 'booked')
                        <span style="color: orange;">Booked</span>
                    @elseif($appt->status === 'completed')
                        <span style="color: green;">Present</span>
                    @elseif($appt->status === 'cancelled')
                        <span style="color: red;">Cancelled</span>
                    @endif
                </td>
                <td>{{ $appt->is_present ? 'Yes' : 'No' }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.appointments.mark', $appt->id) }}" style="display:inline">
                        @csrf
                        <input type="hidden" name="is_present" value="{{ $appt->is_present ? 0 : 1 }}">
                        <button type="submit" onclick="return confirm('{{ $appt->is_present ? 'Revert to booked?' : 'Mark as present?' }}')">
                            {{ $appt->is_present ? 'Revert to Booked' : 'Mark Present' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No upcoming appointments found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>
<a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>

<script>
    $(document).ready(function () {
        $('#appointmentsTable').DataTable({
            pageLength: 10,
            order: [[1, 'asc'], [2, 'asc']], 
            language: {
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ appointments",
                paginate: {
                    previous: "⬅",
                    next: "➡"
                }
            }
        });
    });
</script>

{{-- FullCalendar Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '/appointments/calendar-events',
            eventClick: function (info) {
                const date = info.event.startStr;
                document.getElementById('modalDate').textContent = date;
                document.getElementById('slotModalBody').innerHTML = 'Loading...';

                fetch(`/appointments/schedules-by-date?date=${date}`)
                    .then(res => res.json())
                    .then(slots => {
                        if (slots.length === 0) {
                            document.getElementById('slotModalBody').innerHTML = `<p>No schedules found for this day.</p>`;
                        } else {
                            const html = slots.map(slot => `
                                <div style="margin-bottom: 10px">
                                    <strong>${slot.start_time} - ${slot.end_time}</strong> |
                                    Booked: ${slot.booked} / ${slot.slot_limit}
                                </div>
                            `).join('');
                            document.getElementById('slotModalBody').innerHTML = html;
                        }
                    });

                new bootstrap.Modal(document.getElementById('slotModal')).show();
            }
        });
        calendar.render();
    });
</script>
@endsection
