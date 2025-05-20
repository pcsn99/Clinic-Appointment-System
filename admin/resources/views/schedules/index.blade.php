@extends('layouts.app')

@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="container mt-5" style="max-width: 1000px;">
    <div class="card text-center p-4 mb-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-5">Account Schedule Management</h2>
    </div>

    <div class="card p-4 mb-4" style="border: 2px solid #17224D;">
        <div class="text-center mb-4">
            <a href="{{ route('schedules.create') }}" class="btn text-white mx-2 px-4 py-2"
               style="background-color: #17224D; border-radius: 6px; font-weight: bold;">New Schedule</a>
            <a href="{{ route('schedules.bulk.create') }}" class="btn text-white mx-2 px-4 py-2"
               style="background-color: #17224D; border-radius: 6px; font-weight: bold;">Bulk Create</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center fw-bold">{{ session('success') }}</div>
        @endif

        <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
            @csrf
            <div class="table-responsive">
                <table id="schedulesTable" class="table table-striped table-bordered text-center">
                    <thead class="table-dark">
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
                                <td><input type="checkbox" name="selected[]" value="{{ $schedule->id }}"></td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>{{ $schedule->slot_limit }}</td>
                                <td>
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal for Delete Reason -->
            <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reasonModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="delete_reason">Reason to notify affected users (optional):</label>
                            <textarea name="delete_reason" id="delete_reason" class="form-control" rows="3"
                                placeholder="e.g. Doctor unavailable..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Delete Trigger -->
            <div class="text-center mt-3">
                <button type="button" class="btn btn-danger px-5 py-2" id="bulkDeleteBtn" disabled
                        style="border-radius: 6px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#reasonModal">
                    Delete Selected
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#schedulesTable').DataTable({
            pageLength: 10,
            order: [[1, 'asc']]
        });

        $('#checkAll').on('click', function () {
            const isChecked = this.checked;
            $('input[name="selected[]"]').prop('checked', isChecked);
            toggleBulkDelete();
        });

        $(document).on('change', 'input[name="selected[]"]', toggleBulkDelete);

        function toggleBulkDelete() {
            const anyChecked = $('input[name="selected[]"]:checked').length > 0;
            $('#bulkDeleteBtn').prop('disabled', !anyChecked);
        }
    });
</script>

@endsection
