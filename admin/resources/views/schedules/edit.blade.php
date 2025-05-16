@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">
        {{-- Styled Title Box --}}
        <div class="text-center mb-4 p-3 rounded" style="background-color: #162163; color: white; font-weight: bold; font-size: 24px;">
            Edit Schedule
        </div>

        {{-- Styled Form Section --}}
        <div class="p-4 rounded" style="border: 2px solid #162163; background-color: white;">
            <p class="text-center text-muted">Update the details below to modify the schedule.</p>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Schedule Edit Form --}}
            <form method="POST" action="{{ route('schedules.update', $schedule) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Date Field --}}
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" value="{{ old('date', $schedule->date) }}" required>
                    </div>

                    {{-- Start Time Field (Standard Time Picker) --}}
                    <div class="col-md-3 mb-3">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time', $schedule->start_time) }}" required>
                    </div>

                    {{-- End Time Field (Standard Time Picker) --}}
                    <div class="col-md-3 mb-3">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time', $schedule->end_time) }}" required>
                    </div>
                </div>

                {{-- Slot Limit --}}
                <div class="mb-3">
                    <label for="slot_limit" class="form-label">Slot Limit:</label>
                    <input type="number" id="slot_limit" name="slot_limit" class="form-control" value="{{ old('slot_limit', $schedule->slot_limit) }}" required min="1">
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4" style="background-color: #162163; color: white;">Update Schedule</button>
                </div>
            </form>
        </div>

        {{-- Back to Schedules Button --}}
        <div class="text-center mt-4">
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Back to Schedules</a>
        </div>
    </div>

    {{-- Enhancing User Experience --}}
    <script>
        document.getElementById('start_time').addEventListener('input', function() {
            document.getElementById('end_time').setAttribute('min', this.value);
        });
    </script>

    {{-- Load Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection