@extends('layouts.app')

@section('content')
<h2>Welcome, {{ Auth::user()->name }}</h2>
<p>You are logged in as <strong>{{ Auth::user()->username }}</strong> ({{ Auth::user()->email }})</p>
<p>Course: {{ Auth::user()->course }} | Year: {{ Auth::user()->year }}</p>
<p>Contact: {{ Auth::user()->contact_number }}</p>

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

@if(!$currentBooking || !$currentBooking->is_present)
    <a href="{{ route('student.appointments.index') }}">
        <button class="btn btn-primary">📅 Book Appointment</button>
    </a>
@endif

<hr>

<h4>Today's Available Schedules</h4>
@if($todaySchedules->isEmpty())
    <p>No available schedules today.</p>
@else
    <ul>
        @foreach($todaySchedules as $sched)
            <li>{{ $sched->start_time }} - {{ $sched->end_time }} ({{ $sched->appointments_count }}/{{ $sched->slot_limit }})</li>
        @endforeach
    </ul>
@endif

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
                </div>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger mt-4">Logout</button>
</form>
@endsection
