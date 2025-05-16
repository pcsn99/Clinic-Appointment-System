@extends('layouts.app')

@section('content')
<div>
    <h2>Welcome, Clinic Admin</h2>
    <p>You are logged in as <strong>{{ session('admin')->username }}</strong></p>

    <hr>

    <h3>📅 Appointments Today ({{ now()->format('F j, Y') }})</h3>

    @if($appointmentsToday->isEmpty())
        <p>No appointments scheduled for today.</p>
    @else
        <table border="1" cellpadding="6">
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
                                <span style="color: orange;">Booked</span>
                            @elseif($appt->status === 'completed')
                                <span style="color: green;">Present</span>
                            @elseif($appt->status === 'cancelled')
                                <span style="color: red;">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ $appt->is_present ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h3>🔐 PIN Codes</h3>
    <p><em>These are valid for the current hour:</em></p>
    
    <ul>
        <li><strong>Attendance PIN:</strong> {{ $attendancePin->pin_code ?? 'Not Set' }}</li>
        <li><strong>Slot Limit Override PIN:</strong> {{ $overridePin->pin_code ?? 'Not Set' }}</li>
    </ul>
</div>
@endsection
