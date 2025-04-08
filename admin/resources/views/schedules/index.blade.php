@extends('layouts.app')

@section('content')
    <h2>Schedules</h2>

    {{-- ✅ Back to Dashboard Button --}}
    <a href="{{ route('admin.dashboard') }}">
        <button type="button">← Back to Dashboard</button>
    </a>

    <br><br>

    <a href="{{ route('schedules.create') }}">+ New Schedule</a> |
    <a href="{{ route('schedules.bulk.create') }}">+ Bulk Create</a>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
        @csrf
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Slots</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                <tr>
                    <td>
                        <input type="checkbox" form="bulkDeleteForm" name="selected[]" value="{{ $schedule->id }}">
                    </td>
                    <td>{{ $schedule->date }}</td>
                    <td>{{ $schedule->start_time }}</td>
                    <td>{{ $schedule->end_time }}</td>
                    <td>{{ $schedule->slot_limit }}</td>
                    <td>
                        <a href="{{ route('schedules.edit', $schedule) }}">Edit</a>

                        <form method="POST" action="{{ route('schedules.bulk.delete') }}" style="display:inline">
                            @csrf
                            <input type="hidden" name="selected[]" value="{{ $schedule->id }}">
                            <button type="submit" onclick="return confirm('Delete this schedule?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <button type="submit" form="bulkDeleteForm" onclick="return confirm('Delete selected schedules?')">Delete Selected</button>

    <script>
        document.getElementById('checkAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection
