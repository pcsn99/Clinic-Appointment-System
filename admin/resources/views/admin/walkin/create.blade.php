@extends('layouts.app')

@section('title', 'Walk-In Notification')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-megaphone-fill me-2"></i>Send Walk-In Notification</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <!-- Success Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="successModalLabel">Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <!-- Error Modal -->
                <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <form method="POST" action="{{ route('admin.walkin.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="schedule_id" class="form-label">Select Current Time Slot</label>
                    <select name="schedule_id" class="form-select" required>
                        <option value="" selected disabled>-- Please select a schedule --</option>

                        @forelse($appointments as $appt)
                            <option value="{{ $appt->id }}">
                                {{ $appt->date }} - {{ $appt->start_time }}
                            </option>
                        @empty
                            <option disabled>No appointments available for today</option>
                        @endforelse
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Custom Message (optional)</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Default message will be used if left blank."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-send me-1"></i> Send Notification
                </button>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        @endif

        @if($errors->any())
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        @endif
    });
</script>
@endsection
