@extends('layouts.app')

@section('content')
    <h2>Welcome, {{ session('admin')->name }}</h2>
    <p>You are logged in as <strong>{{ session('admin')->username }}</strong>.</p>

    <hr>

    <h3>Manage System</h3>

    <a href="{{ route('schedules.index') }}">
        <button>📅 Go to Schedule Management</button>
    </a>
    <br><br>

    <a href="{{ route('admin.appointments.create') }}">
        <button>➕ Make Appointment for Student</button>
    </a>
    <br><br>

    <a href="{{ route('admin.appointments.index') }}">
        <button>📋 View Appointments</button>
    </a>

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
    <p><em>PIN codes for students</em></p>

    <hr>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
