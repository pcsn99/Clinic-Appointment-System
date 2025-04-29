@extends('layouts.app')

@section('content')
<style>
    .login-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 60px;
    }

    .login-box {
        background-color: rgba(255, 255, 255, 0.85);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 10px;
        color: #333;
    }

    .login-box p {
        margin-bottom: 30px;
        color: #555;
    }

    .login-box input {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 16px;
    }

    .btn {
        background-color: #1f3c88;
        color: white;
        border: none;
        padding: 12px;
        width: 100%;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #162c63;
    }

    .bottom-text {
        margin-top: 15px;
    }

    .bottom-text a {
        color: #1f3c88;
        text-decoration: none;
    }

    .bottom-text a:hover {
        text-decoration: underline;
    }

    .error {
        color: red;
        font-size: 14px;
    }
</style>

    <h2>Login</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" name="login" placeholder="Email or Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
@endsection
