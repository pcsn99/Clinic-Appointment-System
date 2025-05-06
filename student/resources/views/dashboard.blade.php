@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card">
        <h2 class="text-primary fw-bold display-4">Welcome, {{ Auth::user()->name }}</h2>
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
        <h4 class="fw-bold text-info display-5">Current Appointment</h4>
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

    @if(!$currentBooking || !$currentBooking->is_present)
    <div class="text-center my-4">
        <a href="{{ route('student.appointments.index') }}" class="btn btn-primary btn-lg action-btn">Book Appointment</a>
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
        background-color: #fef9e7;
        border-left: 8px solid #17224D;
    }

    .info-section, .appointment-details {
        font-size: 22px; 
        color: #333;
    }

    .list-group-item {
        background-color: transparent;
        border: none;
        padding: 16px;
    }

    .action-btn {
        width: 100%;
        padding: 16px;
        font-size: 20px; 
        font-weight: bold;
        border-radius: 12px;
    }

    .btn-primary {
        background-color: #17224D;
        border-color: #17224D;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endsection