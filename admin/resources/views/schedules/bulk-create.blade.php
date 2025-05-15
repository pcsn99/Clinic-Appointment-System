@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- ✅ Page Header --}}
        <div class="card p-4 shadow-sm mb-4">
            <h2 class="text-center"> Bulk Create Schedules</h2>
            <p class="text-center text-muted">Generate multiple schedules by setting up parameters below.</p>
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

        {{-- ✅ Bulk Create Form --}}
        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('schedules.bulk.store') }}">
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

                {{-- Interval Dropdown --}}
                <div class="mb-3">
                    <label for="interval" class="form-label">Interval:</label>
                    <select id="interval" name="interval" class="form-control" required>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="90">1 hour 30 minutes</option>
                        <option value="120">2 hours</option>
                    </select>
                </div>

                {{-- Slot Limit per Interval --}}
                <div class="mb-3">
                    <label for="slot_limit" class="form-label">Slot Limit per Interval:</label>
                    <input type="number" id="slot_limit" name="slot_limit" class="form-control" placeholder="Enter slot limit" required min="1">
                </div>

                {{-- ✅ Live Preview of Generated Slots --}}
                <div class="mt-4">
                    <h5 class="text-center">Preview Generated Slots</h5>
                    <ul id="slotPreview" class="list-group text-center"></ul>
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4" style="background-color: #162163; color: white;">Create Schedules</button>
                </div>
            </form>
        </div>

        {{-- ✅ Back Button --}}
        <div class="text-center mt-4">
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Back to Schedules</a>
        </div>
    </div>

    {{-- ✅ Enhancing User Experience --}}
    <script>
        document.getElementById('start_time').addEventListener('input', function () {
            document.getElementById('end_time').setAttribute('min', this.value);
        });

        // Live preview of generated slots based on interval
        document.getElementById('interval').addEventListener('change', function () {
            let startTime = document.getElementById('start_time').value;
            let endTime = document.getElementById('end_time').value;
            let interval = parseInt(this.value);
            let previewList = document.getElementById('slotPreview');
            previewList.innerHTML = "";

            if (startTime && endTime) {
                let currentTime = new Date("2023-01-01 " + startTime);
                let endTimeLimit = new Date("2023-01-01 " + endTime);

                while (currentTime < endTimeLimit) {
                    let timeSlot = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    let listItem = document.createElement('li');
                    listItem.classList.add("list-group-item");
                    listItem.innerText = `Slot: ${timeSlot}`;
                    previewList.appendChild(listItem);

                    currentTime.setMinutes(currentTime.getMinutes() + interval);
                }
            }
        });
    </script>
@endsection