@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card text-center p-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-4">Welcome, {{ Auth::user()->name }}</h2>
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
                            <button class="btn btn-success btn-lg mx-2" data-bs-toggle="modal" data-bs-target="#pinModal">Mark as Present</button>
                        @elseif($currentBooking->is_present)
                            <button class="btn btn-primary btn-lg mx-2" disabled>Upload Certificate (Coming Soon)</button>
                        @endif
                    </div>
                @else
                    <p class="text-muted fs-5">No current booking available.</p>
                @endif
            </div>
        </div>

       
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
@endsection
