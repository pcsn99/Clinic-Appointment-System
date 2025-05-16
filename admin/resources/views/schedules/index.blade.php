@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
       
        <div class="text-center mb-4 p-3 rounded" style="background-color: #162163; color: white; font-weight: bold; font-size: 24px;">
            Account Schedule Management
        </div>

        
        <div class="p-4 mb-4 rounded" style="border: 2px solid #162163; background-color: white;">
      
            <div class="mb-4 text-center">
                <a href="{{ route('schedules.create') }}" class="btn text-white mx-2 px-4 py-2"
                   style="background-color: #162163; border-radius: 6px; font-weight: bold;">New Schedule</a>
                <a href="{{ route('schedules.bulk.create') }}" class="btn text-white mx-2 px-4 py-2"
                   style="background-color: #162163; border-radius: 6px; font-weight: bold;">Bulk Create</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success text-center" style="border-radius: 6px; font-weight: bold;">{{ session('success') }}</div>
            @endif

            <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #162163; color: white; text-align: center;">
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
                                <tr style="text-align: center;">
                                    <td><input type="checkbox" form="bulkDeleteForm" name="selected[]" value="{{ $schedule->id }}"></td>
                                    <td>{{ $schedule->date }}</td>
                                    <td>{{ $schedule->start_time }}</td>
                                    <td>{{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->slot_limit }}</td>
                                    <td>
                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm text-white"
                                           style="background-color: #162163; border-radius: 4px; font-weight: bold;">Edit</a>
                                        <form method="POST" action="{{ route('schedules.bulk.delete') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="selected[]" value="{{ $schedule->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirmDelete('{{ $schedule->date }}')"
                                                    style="border-radius: 4px; font-weight: bold;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="font-style: italic;">No schedules found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="text-center mt-3">
                <button type="submit" form="bulkDeleteForm" class="btn btn-danger px-5 py-2" id="bulkDeleteBtn" disabled
                        style="border-radius: 6px; font-weight: bold;">Delete Selected</button>
            </div>
        </div>
    </div>

    
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