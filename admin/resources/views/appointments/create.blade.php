@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
        
        <div class="text-center mb-4 p-3 rounded" style="background-color: #162163; color: white; font-weight: bold; font-size: 24px;">
            Book Appointment for Student
        </div>

        
        <div class="p-4 rounded" style="border: 2px solid #162163; background-color: white;">
   
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

        
        <div class="text-center mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>
@endsection