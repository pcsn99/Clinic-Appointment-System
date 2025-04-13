@extends('layouts.app')

@section('content')
<style>
    *{
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.85);
      padding: 40px;
      border-radius: 30px;
      width: 400px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
    }

    h1 {
      font-size: 28px;
      color: #1A237E; /* Dark Blue */
      font-weight: bold;
      margin-bottom: 5px;
    }

    h2 {
      font-size: 18px;
      color: #3949AB; /* Slightly Lighter Blue */
      margin-bottom: 30px;
    }
    
</style>

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
