@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card info-card">
        <div class="title-box">
            <h2 class="fw-bold display-4">Account Details</h2>
        </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="student-details">
                        <table class="details-table">
                            <tr>
                                <td class="detail-label">Name:</td>
                                <td class="detail-value">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Username:</td>
                                <td class="detail-value">{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Email:</td>
                                <td class="detail-value">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Course:</td>
                                <td class="detail-value">{{ $user->course }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Year:</td>
                                <td class="detail-value">{{ $user->year }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Contact Number:</td>
                                <td class="detail-value">{{ $user->contact_number }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-center my-4">
                        <a href="{{ route('account.edit') }}" class="btn btn-primary btn-lg action-btn">
                            <i class="bi bi-pencil-square"></i> Edit Profile
                        </a>
                    </div>
    </div>
</div>

<style>
    .container {
        max-width: 1000px;
    }

    .card {
        border: none;
        border-radius: 12px;
        padding: 39px;
        margin-bottom: 30px;
        background-color: #f8f9fa;
    }

    .info-card {
        text-align: center;
        background-color: #ffffff;
        border: 2px solid #dcdcdc;
    }

    .title-box {
        background-color: #17224D;
        color: white;
        padding: 20px;
        text-align: center;
        margin-bottom: 30px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        margin: -40px -40px 30px -40px;
    }

    .student-details {
        padding: 10px;
        margin-bottom: 40px;
    }
    
    .details-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .details-table tr {
        line-height: 1.2;
    }
    
    .detail-label {
        font-weight: bold;
        width: 150px;
        text-align: left;
        padding: 3px 15px 3px 0;
        vertical-align: top;
    }
    
    .detail-value {
        padding: 3px 0;
        vertical-align: top;
    }
    
    .action-btn {
        width: 50%;
        padding: 16px;
        font-size: 20px;
        font-weight: bold;
        border-radius: 15px;
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #17224D;
        border-color: #17224D;
    }

    .row {
        margin-bottom: 10px;
        font-size: 18px;
    }
</style>

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
