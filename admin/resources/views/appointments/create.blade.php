@extends('layouts.app')

@section('content')
<style>
    .appointment-container {
        width: 900px;
        margin: 0 auto;
    }
</style>

<div class="appointment-container">
    <div class="card shadow-lg mb-4">
        <div class="card-header text-center text-white" style="background-color: #17224D;">
            <h2 class="fw-bold display-5 my-2">Book Appointment for Student</h2>
        </div>
        <div class="card-body p-4">
            <!-- Success Popup Modal -->
            @if(session('appointment_success'))
            <div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" style="display: block;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                            <button type="button" class="btn-close btn-close-white" onclick="closeSuccessModal()" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 48px;"></i>
                            <p class="mt-3 mb-0 fs-5">{{ session('appointment_success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            <script>
                // Function to close the modal and redirect to initial state
                function closeSuccessModal() {
                    document.getElementById('successModal').style.display = 'none';
                    document.querySelector('.modal-backdrop').remove();
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    // Redirect to the initial state of the appointment page
                    window.location.href = '{{ route("admin.appointments.create") }}';
                }
                
                // Close the modal after 3 seconds and redirect
                setTimeout(function() {
                    closeSuccessModal();
                }, 3000);
            </script>
            @endif
   
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

    
            <form method="GET" action="{{ route('admin.appointments.create') }}" class="mb-4 text-center">
                <input type="text" name="search" class="form-control d-inline-block w-50" placeholder="Search by name, username, or email">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            @if(count($users))
                <h4 class="text-center">Select Student:</h4>
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $user->name }} ({{ $user->email }})
                            <a href="{{ route('admin.appointments.create', ['user_id' => $user->id]) }}" class="btn btn-sm btn-secondary">Select</a>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($selectedUser ?? false)
                <h3 class="text-center mt-4">Booking for: {{ $selectedUser->name }} ({{ $selectedUser->email }})</h3>

                <form method="POST" action="{{ route('admin.appointments.store') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">

                 
                    <div class="mb-3">
                        <label for="schedule_id" class="form-label">Select Schedule:</label>
                        <select id="schedule_id" name="schedule_id" class="form-control" required>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ $schedule->date }} | {{ $schedule->start_time }} - {{ $schedule->end_time }} ({{ $schedule->slot_limit }} slots)
                                </option>
                            @endforeach
                        </select>
                    </div>

                 
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-4" style="background-color: #162163; color: white;">Book Appointment</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection