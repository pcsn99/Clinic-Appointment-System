@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #17224D; color: white;">
                    <h3 class="mb-0">Login</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">Email or Username</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Enter email or username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" style="background-color: #17224D;">Login</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
