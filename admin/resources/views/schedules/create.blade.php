@extends('layouts.app')

@section('content')
    <h2>Create Schedule</h2>

    @if($errors->any())
        <ul style="color:red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf
        <label>Date: <input type="date" name="date" required></label><br>
        <label>Start Time: <input type="time" name="start_time" required></label><br>
        <label>End Time: <input type="time" name="end_time" required></label><br>
        <label>Slot Limit: <input type="number" name="slot_limit" required min="1"></label><br>
        <button type="submit">Create</button>
    </form>
@endsection
