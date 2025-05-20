@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center" style="background-color: #17224D; color: white;">
                    <h3>Forgot Password</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('forgot.password.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #17224D;">Send New Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
