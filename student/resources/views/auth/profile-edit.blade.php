@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card info-card">
        <h2 class="fw-bold display-4 welcome-heading">Edit Profile</h2>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')



                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control @error('course') is-invalid @enderror" id="course" name="course" value="{{ old('course', $user->course) }}" required>
                            @error('course')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="text" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $user->year) }}" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current password)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('account.show') }}" class="btn btn-secondary btn-lg action-btn-half">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg action-btn-half">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
    </div>
</div>

<style>
    .container {
        max-width: 1000px;
    }

    .card {
        border: none;
        border-radius: 12px;
        padding: 40px;
        margin-bottom: 30px;
        background-color: #f8f9fa;
    }

    .info-card {
        background-color: #ffffff;
        border: 2px solid #dcdcdc;
    }

    .welcome-heading {
        color: #17224D;
        margin-bottom: 30px;
        text-align: center;
    }

    .action-btn-half {
        width: 48%;
        padding: 16px;
        font-size: 20px;
        font-weight: bold;
        border-radius: 12px;
    }

    .btn-primary {
        background-color: #17224D;
        border-color: #17224D;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .form-control {
        padding: 12px;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: bold;
        font-size: 16px;
    }
</style>
@endsection
