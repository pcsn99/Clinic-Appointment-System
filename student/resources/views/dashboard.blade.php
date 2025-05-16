@extends('layouts.app')

@section('content')

<!--Kat added function-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h2>Welcome, {{ Auth::user()->name }}</h2>
            <p>You are logged in as <strong>{{ Auth::user()->username }}</strong> ({{ Auth::user()->email }})</p>
            <p>Course: {{ Auth::user()->course }} | Year: {{ Auth::user()->year }}</p>
            <p>Contact: {{ Auth::user()->contact_number }}</p>

            <!-- Profile buttons removed as they're redundant with the Account button in the sidebar -->

            <hr>

            @if($currentBooking)
                <div class="alert alert-info">
                    <strong>Current Appointment:</strong><br>
                    {{ $currentBooking->schedule->date }} | {{ $currentBooking->schedule->start_time }} - {{ $currentBooking->schedule->end_time }}<br>
                    Status: <strong>{{ ucfirst($currentBooking->status) }}</strong><br>
                    Present: {{ $currentBooking->is_present ? 'Yes' : 'No' }}

                    @if($currentBooking->status === 'booked' && !$currentBooking->is_present)
                        <br><br>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pinModal">✅ Mark as Present</button>
                    @elseif($currentBooking->is_present)
                        <br><br>
                        <button class="btn btn-primary" disabled>📎 Upload Certificate (Coming Soon)</button>
                    @endif
                </div>
            @endif

            <!-- Book Appointment button removed as it's now in the sidebar -->

            

            <h4>Today's Available Schedules</h4>
            @if($todaySchedules->isEmpty())
                <p>No available schedules today.</p>
            @else
                <ul class="list-group">
                    @foreach($todaySchedules as $sched)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $sched->start_time }} - {{ $sched->end_time }}
                            <span class="badge bg-primary rounded-pill">{{ $sched->appointments_count }}/{{ $sched->slot_limit }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

{{-- PIN Modal --}}
<div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('student.appointments.markPresent', $currentBooking->id ?? 0) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enter PIN to Mark as Present</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="pin">PIN:</label>
                    <input type="text" name="pin" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

<div class="container mt-5">
    <div class="card info-card">
        <h2 class="fw-bold display-4 welcome-heading">Welcome, {{ Auth::user()->name }}</h2>
        <div class="info-section fs-4">
            <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Course:</strong> {{ Auth::user()->course }}</p>
            <p><strong>Year:</strong> {{ Auth::user()->year }}</p>
            <p><strong>Contact:</strong> {{ Auth::user()->contact_number }}</p>
        </div>
    </div>

    @if($currentBooking)
    <div class="card appointment-card">
        <h4 class="fw-bold display-5 current-appointment">Current Appointment</h4>
        <div class="appointment-details fs-4">
            <p><strong>Date:</strong> {{ $currentBooking->schedule->date }}</p>
            <p><strong>Time:</strong> {{ $currentBooking->schedule->start_time }} - {{ $currentBooking->schedule->end_time }}</p>
            <p><strong>Status:</strong> {{ ucfirst($currentBooking->status) }}</p>
            <p><strong>Present:</strong> {{ $currentBooking->is_present ? 'Yes' : 'No' }}</p>
        </div>

        @if($currentBooking->status === 'booked' && !$currentBooking->is_present)
            <button class="btn btn-success action-btn btn-lg" data-bs-toggle="modal" data-bs-target="#pinModal">Mark as Present</button>
        @elseif($currentBooking->is_present)
            <button class="btn btn-primary action-btn btn-lg" disabled>Upload Certificate (Coming Soon)</button>
        @endif
    </div>
    @endif

    <div class="card schedule-card">
        <h4 class="fw-bold display-5">Today's Available Schedules</h4>
        @if($todaySchedules->isEmpty())
            <p class="text-muted fs-4">No available schedules today.</p>
        @else
            <ul class="list-group">
                @foreach($todaySchedules as $sched)
                    <li class="list-group-item d-flex justify-content-between align-items-center fs-4">
                        <strong>{{ $sched->start_time }} - {{ $sched->end_time }}</strong>
                        <span class="badge bg-primary fs-4">{{ $sched->appointments_count }}/{{ $sched->slot_limit }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Book Appointment button removed as it's now in the sidebar -->
    </div>

    <div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('student.appointments.markPresent', $currentBooking->id ?? 0) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold display-5">Enter PIN to Mark as Present</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="pin" class="form-label fs-4">PIN:</label>
                        <input type="text" name="pin" class="form-control fs-4" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success action-btn btn-lg">Submit</button>
                        <button type="button" class="btn btn-secondary action-btn btn-lg" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
    </div>
</div>


<form method="POST" action="{{ route('logout') }}" class="mt-4">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>



@endsection