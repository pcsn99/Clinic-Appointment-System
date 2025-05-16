@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 1000px;">
    <!-- Header -->
    <div class="card text-center p-4 mb-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-5">Account Schedule Management</h2>
    </div>

    <!-- Actions -->
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

        <!-- Filter/Search -->
        <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control me-2 mb-2" placeholder="Search schedules..." style="flex: 1 1 200px;">
            <select id="filterSelect" class="form-select me-2 mb-2" style="flex: 1 1 150px;">
                <option value="all">All</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
            </select>
            <button id="searchBtn" class="btn text-white mb-2" style="background-color: #17224D;">Search</button>
        </div>

        <!-- Table -->
        <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                    <thead class="table-light">
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
                            <tr class="schedule-row" data-date="{{ $schedule->date }}">
                                <td><input type="checkbox" form="bulkDeleteForm" name="selected[]" value="{{ $schedule->id }}"></td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>{{ $schedule->slot_limit }}</td>
                                <td>
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm text-white"
                                       style="background-color: #17224D; border-radius: 4px; font-weight: bold;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('schedules.bulk.delete') }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="selected[]" value="{{ $schedule->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirmDelete('{{ $schedule->date }}')"
                                                style="border-radius: 4px; font-weight: bold;">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted fst-italic">No schedules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Delete -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-danger px-5 py-2" id="bulkDeleteBtn" disabled
                        style="border-radius: 6px; font-weight: bold;">Delete Selected</button>
            </div>
        </form>

        <!-- Pagination Info & Controls -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $schedules->firstItem() ?? 0 }} to {{ $schedules->lastItem() ?? 0 }} of {{ $schedules->total() }} entries
            </div>
            <div class="pagination justify-content-end">
                {{ $schedules->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
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

    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const searchBtn = document.getElementById('searchBtn');
    const scheduleRows = document.querySelectorAll('.schedule-row');

    searchBtn.addEventListener('click', function () {
        const searchTerm = searchInput.value.toLowerCase();
        scheduleRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    });

    filterSelect.addEventListener('change', function () {
        const filterValue = filterSelect.value;
        const today = new Date().toISOString().split('T')[0];

        scheduleRows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            if (filterValue === 'all') row.style.display = '';
            else if (filterValue === 'upcoming' && rowDate >= today) row.style.display = '';
            else if (filterValue === 'past' && rowDate < today) row.style.display = '';
            else row.style.display = 'none';
        });
    });
</script>
@endsection
