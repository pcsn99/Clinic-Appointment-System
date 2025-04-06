@extends('layouts.app')

@section('content')
    <h2>Register</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br>
        <input type="text" name="course" placeholder="Course" required><br>
        <input type="text" name="year" placeholder="Year Level" required><br>
        <input type="text" name="contact_number" placeholder="Contact Number" required><br>
        <button type="submit">Register</button>
    </form>

    <p>Already registered? <a href="{{ route('login') }}">Login here</a></p>
@endsection
