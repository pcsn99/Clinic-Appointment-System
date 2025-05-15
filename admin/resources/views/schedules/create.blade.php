@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- ✅ Page Header --}}
        <div class="card p-4 shadow-sm mb-4">
            <h2 class="text-center">Create a New Schedule</h2>
            <p class="text-center text-muted">Fill in the details below to set up a new schedule.</p>
        </div>

        {{-- ✅ Error Messages --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Schedule Form --}}
        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('schedules.store') }}">
                @csrf
                <div class="row">
                    {{-- Date Field --}}
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-3 mb-3">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>

                    {{-- End Time --}}
                    <div class="col-md-3 mb-3">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>

                {{-- Slot Limit --}}
                <div class="mb-3">
                    <label for="slot_limit" class="form-label">Slot Limit:</label>
                    <input type="number" id="slot_limit" name="slot_limit" class="form-control" required min="1">
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4" style="background-color: #162163; color: white;">Create Schedule</button>
                </div>
            </form>
        </div>

        {{-- ✅ Back Button --}}
        <div class="text-center mt-4">
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary"  >Back to Schedules</a>
        </div>
    </div>

    {{-- ✅ Enhancing User Experience --}}
    <script>
        document.getElementById('start_time').addEventListener('input', function() {
            document.getElementById('end_time').setAttribute('min', this.value);
        });
    </script>
@endsection