@extends('layouts.app')

@section('content')
    <h2>Welcome, {{ Auth::user()->name }}</h2>
    <p>You are logged in as <strong>{{ Auth::user()->username }}</strong> ({{ Auth::user()->email }})</p>

    <p>Course: {{ Auth::user()->course }} | Year: {{ Auth::user()->year }}</p>
    <p>Contact: {{ Auth::user()->contact_number }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
