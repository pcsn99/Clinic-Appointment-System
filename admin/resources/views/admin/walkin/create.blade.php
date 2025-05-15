@extends('layouts.app')

@section('title', 'Walk-In Notification')

@section('content')
<div class="container">
    <h2>Send Walk-In Notification</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.walkin.store') }}">
        @csrf

        <div class="form-group">
            <label for="appointment_id">Select Schedule (Today)</label>
            <select name="appointment_id" class="form-control" required>
                @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}">
                        {{ $appt->appointment_date }} - {{ $appt->schedule_time }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="message">Custom Message (optional)</label>
            <textarea name="message" class="form-control" rows="3" placeholder="Default message will be used if left blank."></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Send Notification</button>
    </form>
</div>
@endsection
