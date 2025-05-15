@extends('layouts.app')

@section('body_background', "url('" . asset('src/xu.png') . "') no-repeat center center fixed")

@section('content')

<div class="login-container">
    <div class="login-box">
        <h2>Hello!<br>Welcome Back!</h2>
        <p>Let's Login to Your Account</p>

        @if($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="login" placeholder="Email or Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="bottom-text">
            <p>Don’t have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</div>
@endsection
