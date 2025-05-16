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

    <div class="text-center my-4">
        <a href="{{ route('profile') }}" class="btn btn-primary mx-2">View Full Profile</a>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary mx-2">Edit Profile</a>
    </div>

    <div class="row mt-4">
        <!-- Current Appointment Section -->
        <div class="col-md-6">
            <div class="card appointment-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Current Appointment</h4>
                @if($currentBooking)
                    <div class="fs-4">
                        <p><strong>Date:</strong> {{ $currentBooking->schedule->date }}</p>
                        <p><strong>Time:</strong> {{ $currentBooking->schedule->start_time }} - {{ $currentBooking->schedule->end_time }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($currentBooking->status) }}</p>
                        <p><strong>Present:</strong> {{ $currentBooking->is_present ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="text-center mt-3">
                        @if($currentBooking->status === 'booked' && !$currentBooking->is_present)
                            <button class="btn btn-success btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#pinModal">✅ Mark as Present</button>
                        @elseif($currentBooking->is_present)
                            <button class="btn btn-primary btn-lg mx-2" disabled>📎 Upload Certificate (Coming Soon)</button>
                        @endif
                    </div>
                @else
                    <p class="text-muted fs-5">No current booking available.</p>
                @endif

                @if(!$currentBooking || !$currentBooking->is_present)
                    <a href="{{ route('student.appointments.index') }}" class="btn btn-primary mt-3">📅 Book Appointment</a>
                @endif
            </div>
        </div>

        <!-- Today's Available Schedules Section -->
        <div class="col-md-6">
            <div class="card schedule-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Today's Available Schedules</h4>
                @if($todaySchedules->isEmpty())
                    <p class="text-muted fs-5">No available schedules today.</p>
                @else
                    <ul class="list-group">
                        @foreach($todaySchedules as $sched)
                            <li class="list-group-item d-flex justify-content-between align-items-center fs-5">
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

<!-- PIN Modal -->
@if($currentBooking && $currentBooking->status === 'booked' && !$currentBooking->is_present)
<div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
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

@endsection
