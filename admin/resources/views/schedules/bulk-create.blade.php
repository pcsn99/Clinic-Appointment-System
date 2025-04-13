@extends('layouts.app')

@section('content')
    <h2>Bulk Create Schedules</h2>

    <form method="POST" action="{{ route('schedules.bulk.store') }}">
        @csrf

        <label>Date: <input type="date" name="date" required></label><br><br>

        <label>Start Time: <input type="time" name="start_time" required></label><br>
        <label>End Time: <input type="time" name="end_time" required></label><br>

        <label>Interval:
            <select name="interval" required>
                <option value="30">30 minutes</option>
                <option value="60">1 hour</option>
                <option value="90">1 hour 30 minutes</option>
                <option value="120">2 hours</option>
            </select>
        </label><br>

        <label>Slot Limit per Interval: <input type="number" name="slot_limit" min="1" required></label><br><br>

        <button type="submit">Create Schedules</button>
    </form>
@endsection
