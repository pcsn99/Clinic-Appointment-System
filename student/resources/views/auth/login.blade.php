@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-100 to-blue-100">
    <div class="bg-white bg-opacity-50 backdrop-blur-md rounded-2xl shadow-xl px-8 py-12 w-full max-w-md text-center">
        <h1 class="text-3xl font-bold text-indigo-900 mb-1">Hello!</h1>
        <h2 class="text-2xl font-semibold text-indigo-800 mb-4">Welcome Back!</h2>
        <p class="text-md text-indigo-700 mb-6">Let’s Login to Your Account</p>


        @if($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
   
    </div>
</div>
@endsection
