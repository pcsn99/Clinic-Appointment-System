@extends('layouts.app')

@section('content')
    <h2>Upcoming Appointments</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    {{-- Create new appointment --}}
    <a href="{{ route('admin.appointments.create') }}">
        <button>➕ Create Appointment for Student</button>
    </a>

    <br><br>

    {{-- Bulk delete form --}}
    <form method="POST" action="{{ route('admin.appointments.bulkDelete') }}" id="bulkDeleteForm">
        @csrf

        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Present</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                    <tr>
                        <td><input type="checkbox" name="selected[]" value="{{ $appt->id }}"></td>
                        <td>{{ $appt->user->name }}</td>
                        <td>{{ $appt->schedule->date }}</td>
                        <td>{{ $appt->schedule->start_time }} - {{ $appt->schedule->end_time }}</td>
                        <td>{{ ucfirst($appt->status) }}</td>
                        <td>{{ $appt->is_present ? 'Yes' : 'No' }}</td>
                        <td>
                            {{-- Toggle Present / Revert --}}
                            <form method="POST" action="{{ route('admin.appointments.mark', $appt->id) }}" style="display:inline">
                                @csrf
                                <input type="hidden" name="is_present" value="{{ $appt->is_present ? 0 : 1 }}">
                                <button type="submit" onclick="return confirm('{{ $appt->is_present ? 'Revert to booked?' : 'Mark as present?' }}')">
                                    {{ $appt->is_present ? 'Revert to Booked' : 'Mark Present' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No upcoming appointments.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <br>
        <button type="submit" onclick="return confirm('Are you sure you want to delete the selected appointments?')">
            🗑️ Delete Selected
        </button>
    </form>

    <br><br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>

    <script>
        document.getElementById('checkAll').addEventListener('change', function () {
            document.querySelectorAll('input[name="selected[]"]').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
