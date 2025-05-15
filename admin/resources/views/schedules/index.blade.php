@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
        <div class="border rounded shadow p-4" style="border-color: #3f51b5;">
            <h2 class="text-center mb-4" style="color: #162163;">Schedule Management</h2>

            {{-- ✅ Back to Dashboard Button --}}
            <div class="mb-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-dark" style="background-color: #162163;">Back to Dashboard</a>
            </div>

            {{-- Action Buttons --}}
            <div class="mb-4 text-center">
                <a href="{{ route('schedules.create') }}" class="btn text-white mx-2" style="background-color: #162163;">New Schedule</a>
                <a href="{{ route('schedules.bulk.create') }}" class="btn text-white mx-2" style="background-color: #162163;">Bulk Create</a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- Schedule Table --}}
            <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #3f51b5; color: white;">
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
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td><input type="checkbox" form="bulkDeleteForm" name="selected[]" value="{{ $schedule->id }}"></td>
                                    <td>{{ $schedule->date }}</td>
                                    <td>{{ $schedule->start_time }}</td>
                                    <td>{{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->slot_limit }}</td>
                                    <td>
                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-warning btn-sm" style="background-color: #162163; color: white;">Edit</a>
                                        <form method="POST" action="{{ route('schedules.bulk.delete') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="selected[]" value="{{ $schedule->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDelete('{{ $schedule->date }}')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No schedules found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- Delete Selected Button --}}
            <div class="text-center mt-3">
                <button type="submit" form="bulkDeleteForm" class="btn btn-danger px-4" id="bulkDeleteBtn" disabled>Delete Selected</button>
            </div>
        </div>
    </div>

    {{-- JavaScript for checkbox logic --}}
    <script>
        document.getElementById('checkAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="selected[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDelete();
        });

        document.querySelectorAll('input[name="selected[]"]').forEach(cb => {
            cb.addEventListener('change', toggleBulkDelete);
        });

        function toggleBulkDelete() {
            document.getElementById('bulkDeleteBtn').disabled = !document.querySelectorAll('input[name="selected[]"]:checked').length;
        }

        function confirmDelete(date) {
            return confirm(`Are you sure you want to delete the schedule on ${date}?`);
        }
    </script>
@endsection
