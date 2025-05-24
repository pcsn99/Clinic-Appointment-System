@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card text-center p-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-4">Welcome, {{ Auth::user()->name }}</h2>
        <p class="mb-0">Logged in as <strong>{{ Auth::user()->username }}</strong> | {{ Auth::user()->email }}</p>
        <p class="mb-0">Course: {{ Auth::user()->course }} | Year: {{ Auth::user()->year }} | Contact: {{ Auth::user()->contact_number }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <!-- Profile buttons removed as they're already in the sidebar -->

    <div class="row mt-4">
        <!-- Current Appointment -->
        <div class="col-md-6">
            <div class="card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Current Appointment</h4>
                @if($currentBooking)
                    <div class="fs-4">
                        <p><strong>Date:</strong> {{ $currentBooking->schedule->date }}</p>
                        <p><strong>Time:</strong> {{ $currentBooking->schedule->start_time }} - {{ $currentBooking->schedule->end_time }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($currentBooking->status) }}</p>
                        <p><strong>Present:</strong> {{ $currentBooking->is_present ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($currentBooking->status === 'booked' && !$currentBooking->is_present)
                        <button class="btn btn-success btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#pinModal">✅ Mark as Present</button>
                    @elseif($currentBooking->is_present)
                        
                    @endif
                @else
                    <p class="text-muted fs-5">No current booking available.</p>
                @endif

                <!-- Book Appointment button removed as it's already in the sidebar -->
            </div>
        </div>

        <!-- Available Schedules Today -->
        <div class="col-md-6">
            <div class="card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Today's Available Schedules</h4>
                @if($todaySchedules->isEmpty())
                    <p class="text-muted fs-5">No available schedules today.</p>
                @else
                    <ul class="list-group">
                        @foreach($todaySchedules as $sched)
                            <li class="list-group-item d-flex justify-content-between align-items-center fs-5 schedule-slot"
                                data-id="{{ $sched->id }}"
                                data-start="{{ $sched->start_time }}"
                                data-end="{{ $sched->end_time }}"
                                data-booked="{{ $sched->appointments_count }}"
                                data-limit="{{ $sched->slot_limit }}"
                                style="cursor: pointer;">
                                <strong>{{ $sched->start_time }} - {{ $sched->end_time }}</strong>
                                <span class="badge bg-primary fs-5">{{ $sched->appointments_count }}/{{ $sched->slot_limit }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="bookingForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17224D; color: white;">
                    <h5 class="modal-title fw-bold">Schedule Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body fs-5">
                    <div id="reschedWarning" class="alert alert-warning" style="display: none;">
                        <strong>⚠️ You already have a booking.</strong><br>
                        Proceeding will <strong>rebook</strong> your current appointment to this new schedule.
                    </div>

                    <p id="bookingDetails"></p>
                    <input type="hidden" name="schedule_id" id="modalScheduleId">
                    <input type="hidden" name="mode" id="modalMode">
                    <div id="pinInputBox" class="mt-2" style="display: none;">
                        <label>Override PIN (Required for Full Slots)</label>
                        <input type="text" name="pin" class="form-control">
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

@if($currentBooking && $currentBooking->status === 'booked' && !$currentBooking->is_present)
<!-- PIN Modal for Marking Presence -->
<div class="modal fade" id="pinModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('student.appointments.markPresent', $currentBooking->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enter PIN to Mark as Present</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="pin" class="form-control" placeholder="Enter PIN" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    // This is standard Blade syntax - ignore any linting errors as they don't affect functionality
    
    document.querySelectorAll('.schedule-slot').forEach(item => {
        item.addEventListener('click', () => {
            const scheduleId = item.dataset.id;
            const start = item.dataset.start;
            const end = item.dataset.end;
            const booked = parseInt(item.dataset.booked);
            const limit = parseInt(item.dataset.limit);

            const isFull = booked >= limit;
            const hasBooking = @json($currentBooking !== null && $currentBooking->status === 'booked');

            const form = document.getElementById('bookingForm');
            const action = hasBooking 
                ? `/appointments/{{ $currentBooking?->id ?? 0 }}/reschedule`
                : `{{ route('student.appointments.book') }}`;

            form.action = action;
            document.getElementById('modalScheduleId').value = scheduleId;
            document.getElementById('modalMode').value = hasBooking ? 'reschedule' : 'book';

            document.getElementById('bookingDetails').innerHTML =
                `<strong>${start} - ${end}</strong><br>` +
                (isFull ? `<span class='text-danger'>Slot is full. PIN required to override.</span>` : '');

            document.getElementById('pinInputBox').style.display = isFull ? 'block' : 'none';
            document.getElementById('reschedWarning').style.display = hasBooking ? 'block' : 'none';

            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        });
    });
</script>

@endsection
