@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <!-- Welcome Section in a separate container -->
    <div class="card p-4 mb-4 text-center text-white" style="background-color: #162163;">
        <h2 class="fw-bold display-4 text-uppercase">Welcome, {{ Auth::user()->name }}</h2>
    </div>

    <!-- User Details in another container -->
    <div class="card info-card p-4 bg-light">
        <div class="info-section fs-4">
            <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Course:</strong> {{ Auth::user()->course }}</p>
            <p><strong>Year:</strong> {{ Auth::user()->year }}</p>
            <p><strong>Contact:</strong> {{ Auth::user()->contact_number }}</p>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('profile') }}" class="btn btn-primary btn-lg mx-2">View Full Profile</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg mx-2">Edit Profile</a>
        </div>
    </div>

    <!-- Appointment Section -->
    @if($currentBooking)
    <div class="card appointment-card p-4 mt-4 bg-light">
        <h4 class="fw-bold display-5 text-primary text-center">Current Appointment</h4>
        <hr>
        <div class="appointment-details fs-4">
            <p><strong>Date:</strong> {{ $currentBooking->schedule->date }}</p>
            <p><strong>Time:</strong> {{ $currentBooking->schedule->start_time }} - {{ $currentBooking->schedule->end_time }}</p>
            <p><strong>Status:</strong> {{ ucfirst($currentBooking->status) }}</p>
            <p><strong>Present:</strong> {{ $currentBooking->is_present ? 'Yes' : 'No' }}</p>
        </div>

        <div class="text-center mt-4">
            @if($currentBooking->status === 'booked' && !$currentBooking->is_present)
                <button class="btn btn-success btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#pinModal">Mark as Present</button>
            @elseif($currentBooking->is_present)
                <button class="btn btn-primary btn-lg mx-2" disabled>Upload Certificate (Coming Soon)</button>
            @endif
        </div>
    </div>
    @endif

    <!-- Available Schedules in a separate container -->
    <div class="card schedule-card p-4 mt-4 bg-light">
        <h4 class="fw-bold display-5 text-primary text-center">Today's Available Schedules</h4>
        <hr>
        @if($todaySchedules->isEmpty())
        
            <p class="text-muted fs-4 text-center">No available schedules today.</p>
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

        @if(!$currentBooking || !$currentBooking->is_present)
        <div class="text-center my-4">
            <a href="{{ route('student.appointments.index') }}" class="btn btn-primary btn-lg mx-2">📅 Book Appointment</a>
        </div>
        @endif
    </div>

    <!-- Logout Button -->
    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-lg mx-2">Logout</button>
        </form>
    </div>
</div>

@endsection