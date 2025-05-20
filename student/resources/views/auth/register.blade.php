@extends('layouts.app')

@section('body_background', "url('" . asset('src/xu.png') . "') no-repeat center center fixed")

@section('content')

<style>
    body {
        position: relative;
        background-size: cover;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.3); 
        z-index: -1; 
    }
</style>

<script>
    
    document.body.style.background = "url('{{ asset('src/xu.png') }}') no-repeat center center fixed";
</script>

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
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+63</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="contact_number"
                                    name="contact_number"
                                    placeholder="9123456789"
                                    pattern="\d{10}"
                                    maxlength="10"
                                    required
                                >
                            </div>
                            <small class="form-text text-muted">Enter a 10-digit number (e.g., 9123456789)</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="registerBtn" style="background-color: #17224D;">Register</button>
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

<script>
    const form = document.querySelector('form');
    const registerBtn = document.getElementById('registerBtn');

    form.addEventListener('submit', function () {
        registerBtn.disabled = true;
        registerBtn.innerText = 'Registering...';
    });
</script>
@endsection