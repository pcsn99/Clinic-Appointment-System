@extends('layouts.app')

@section('content')
<style>
    *{
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
     background:url("{{ asset('xubg/xavier.png') }}");
     background-size: cover;
     min-height: 100vh;
     display: flex;
     align-items: center;
     justify-content: center;
    }
    
    .login-container {
      background-color: rgba(255, 255, 255, 0.6);
      padding: 60px 40px;
      border-radius: 30px;
      max-width: 400px;
      width: 100%;
      text-align: center;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
    }

    h1 {
      font-size: 32px;
      color: #1A237E; /* Dark Blue */
      font-weight: bold;
      margin-bottom: 5px;
    }

    h2 {
      font-size: 20px;
      color: #3949AB; /* Slightly Lighter Blue */
      margin-bottom: 30px;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 0 #1A237E;
      font-size: 15px;
    }

    .login-btn {
      background-color: #1A237E;
      color: white;
      border: none;
      padding: 10px 0;
      width: 100%;
      font-size: 18px;
      border-radius: 12px;
      cursor: pointer;
    }

    .login-btn:hover {
      background-color: #0D153A;
    }

    .register-link {
    margin-top: 15px;
    font-size: 14px;
  }

  .register-link a {
    color: #1A237E;
    text-decoration: none;
  }

  .register-link a:hover {
    text-decoration: underline;
  }
  @media (max-width: 600px) {
  .login-container {
    padding: 40px 20px;
  }

  h1 {
    font-size: 26px;
  }

  h2 {
    font-size: 16px;
  }
}
</style>

<div class="login-container">
  <h1>Hello!<br>Welcome Back!</h1>
  <h2>Let's Login to Your Account</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="text" name="login" placeholder="Email or Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <div class="register-link">
    Don’t have an account? <a href="{{ route('register') }}">Register here</a>
  </div>
</div>
    
@endsection
