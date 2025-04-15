@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Student Profile</div>

                <div class="card-body">
                    <div class="mb-3">
                        <h4>{{ $student->name }}</h4>
                        <p class="text-muted">{{ $student->username }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Email:</strong> {{ $student->email }}
                    </div>

                    <div class="mb-3">
                        <strong>Course:</strong> {{ $student->course }}
                    </div>

                    <div class="mb-3">
                        <strong>Year:</strong> {{ $student->year }}
                    </div>

                    <div class="mb-3">
                        <strong>Contact Number:</strong> {{ $student->contact_number }}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
