@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px;">

        <div class="text-center mb-4 p-3 rounded" style="background-color: #162163; color: white; font-weight: bold; font-size: 24px;">
            Bulk Create Schedules
        </div>
        <div class="p-4 rounded" style="border: 2px solid #162163; background-color: white;">
            <p class="text-center text-muted">Generate multiple schedules by setting up parameters below.</p>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('schedules.bulk.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>

          
                <div class="mb-3">
                    <label for="interval" class="form-label">Interval:</label>
                    <select id="interval" name="interval" class="form-control" required>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="90">1 hour 30 minutes</option>
                        <option value="120">2 hours</option>
                    </select>
                </div>

        
                <div class="mb-3">
                    <label for="slot_limit" class="form-label">Slot Limit per Interval:</label>
                    <input type="number" id="slot_limit" name="slot_limit" class="form-control" placeholder="Enter slot limit" required min="1">
                </div>

           
                <div class="mt-4">
                    <h5 class="text-center">Preview Generated Slots</h5>
                    <ul id="slotPreview" class="list-group text-center"></ul>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4" style="background-color: #162163; color: white;">Create Schedules</button>
                </div>
            </form>
        </div>

   
        <div class="text-center mt-4">
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Back to Schedules</a>
        </div>
    </div>

   
    <script>
        document.getElementById('start_time').addEventListener('input', function () {
            document.getElementById('end_time').setAttribute('min', this.value);
        });

        
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