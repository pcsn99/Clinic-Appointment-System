@extends('layouts.app')

@section('body_background', "url('" . asset('src/xu.png') . "') no-repeat center center fixed")

@section('content')
<style>
    body {
        position: relative;
        background: url('{{ asset('src/xu.png') }}') no-repeat center center fixed;
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center" style="background-color: #17224D; color: white;">
                    <h2>Hello!</h2>
                    <p class="mb-0">Let's Login to Your Account</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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
                        <p class="mb-0">Don’t have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></p>
                    </div>
                    <div class="mt-2 text-center">
                        <a href="{{ route('forgot.password') }}" class="text-decoration-none">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection