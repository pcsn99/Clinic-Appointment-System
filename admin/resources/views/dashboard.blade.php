@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card text-center p-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-4">Welcome, Clinic Admin</h2>
    </div>

    <div class="row mt-4">
        <!-- Appointments Section -->
        <div class="col-md-6">
            <div class="card appointment-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Appointments Today</h4>
                <p class="text-muted">{{ now()->format('F j, Y') }}</p>
                
                @if($appointmentsToday->isEmpty())
                    <p class="text-muted fs-5">No appointments scheduled for today.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Present</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointmentsToday as $appt)
                                    <tr>
                                        <td>{{ $appt->user->name }}</td>
                                        <td>{{ $appt->schedule->start_time }} - {{ $appt->schedule->end_time }}</td>
                                        <td>
                                            @if($appt->status === 'booked')
                                                <span class="badge bg-warning text-dark">Booked</span>
                                            @elseif($appt->status === 'completed')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($appt->status === 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $appt->is_present ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- PIN Codes Section -->
        <div class="col-md-6">
            <div class="card schedule-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">PIN Codes</h4>
                <p class="text-muted">Valid for the current hour</p>
                
                <div class="fs-4 mt-3">
                    <p><strong>Attendance PIN:</strong> {{ $attendancePin->pin_code ?? 'Not Set' }}</p>
                    <p><strong>Slot Limit Override PIN:</strong> {{ $overridePin->pin_code ?? 'Not Set' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
