@extends('layouts.app')

@section('content')
    <h2>Book Appointment for Student</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    {{-- Search User --}}
    <form method="GET" action="{{ route('admin.appointments.create') }}">
        <input type="text" name="search" placeholder="Search by name, username, or email">
        <button type="submit">Search</button>
    </form>

    @if(count($users))
        <h4>Select Student:</h4>
        <ul>
            @foreach($users as $user)
                <li>
                    {{ $user->name }} ({{ $user->email }}) 
                    <a href="{{ route('admin.appointments.create', ['user_id' => $user->id]) }}">Select</a>
                </li>
            @endforeach
        </ul>
    @endif

    @if($selectedUser ?? false)
        <h3>Booking for: {{ $selectedUser->name }} ({{ $selectedUser->email }})</h3>

        <form method="POST" action="{{ route('admin.appointments.store') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">

            <label>Select Schedule:</label>
            <select name="schedule_id" required>
                @foreach($schedules as $schedule)
                    <option value="{{ $schedule->id }}">
                        {{ $schedule->date }} | {{ $schedule->start_time }} - {{ $schedule->end_time }} ({{ $schedule->slot_limit }} slots)
                    </option>
                @endforeach
            </select><br><br>

            <button type="submit">Book Appointment</button>
        </form>
    @endif

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
@endsection
