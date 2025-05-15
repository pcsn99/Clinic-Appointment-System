@extends('layouts.app')

@section('body_background', "url('" . asset('src/xu.png') . "') no-repeat center center fixed")

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #17224D; color: white;">
                    <h3 class="mb-0">Register</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="college" class="form-label">Select College</label>
                            <select class="form-control" id="college" name="college" required>
                                <option value="">Select College</option>
                                <!-- Add college options here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Select Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="">Select Course</option>
                                <!-- Add course options here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" style="background-color: #17224D;">Register</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p class="mb-0">Already registered? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection