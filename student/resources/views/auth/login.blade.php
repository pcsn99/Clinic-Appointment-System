@extends('layouts.app')

@section('content')
<style>
    {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
