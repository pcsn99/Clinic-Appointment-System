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

        <!-- Search & Filter -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchInput" class="form-control me-2" placeholder="Search schedules...">
            <select id="filterSelect" class="form-select me-2">
                <option value="all">All</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
            </select>
            <button id="searchBtn" class="btn text-white" style="background-color: #162163;">Search</button>
        </div>

        <form id="bulkDeleteForm" method="POST" action="{{ route('schedules.bulk.delete') }}">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-hover student-table">
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
                        @foreach($schedules as $schedule)
                            <tr class="schedule-row" data-date="{{ $schedule->date }}">
                                <td><input type="checkbox" form="bulkDeleteForm" name="selected[]" value="{{ $schedule->id }}"></td>
                                <td>{{ $schedule->date }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>{{ $schedule->slot_limit }}</td>
                                <td>
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('schedules.bulk.delete') }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="selected[]" value="{{ $schedule->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirmDelete('{{ $schedule->date }}')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <div class="text-center mt-3">
            <button type="submit" form="bulkDeleteForm" class="btn btn-danger px-5 py-2" id="bulkDeleteBtn" disabled
                    style="border-radius: 6px; font-weight: bold;">Delete Selected</button>
        </div>

        <!-- Pagination Controls (Now Outside the Form) -->
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-primary" id="prevPage">Previous</button>
            <span id="pageIndicator" class="mx-3">Page 1 out of 1</span>
            <button class="btn btn-primary" id="nextPage">Next</button>
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

    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const searchBtn = document.getElementById('searchBtn');
    const scheduleRows = document.querySelectorAll('.schedule-row');
    const itemsPerPage = 5;
    let currentPage = 1;

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

    function paginate() {
        const totalPages = Math.ceil(scheduleRows.length / itemsPerPage);
        
        scheduleRows.forEach((row, index) => {
            row.style.display = (index >= (currentPage - 1) * itemsPerPage && index < currentPage * itemsPerPage) ? '' : 'none';
        });

        document.getElementById('pageIndicator').textContent = `Page ${currentPage} out of ${totalPages}`;
    }

    document.getElementById('prevPage').addEventListener('click', function (event) {
        event.preventDefault(); 
        if (currentPage > 1) {
            currentPage--;
            paginate();
        }
    });

    document.getElementById('nextPage').addEventListener('click', function (event) {
        event.preventDefault(); 
        const totalPages = Math.ceil(scheduleRows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            paginate();
        }
    });

    paginate();
</script>
@endsection