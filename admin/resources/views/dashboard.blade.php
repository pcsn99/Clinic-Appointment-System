@extends('layouts.app')

@section('content')
    <h2>Welcome, {{ session('admin')->name }}</h2>
    <p>You are logged in as <strong>{{ session('admin')->username }}</strong>.</p>

    <hr>

    <h3>Manage System</h3>

    {{-- ✅ Schedule Management --}}
    <a href="{{ route('schedules.index') }}">
        <button>📅 Go to Schedule Management</button>
    </a>

    <br><br>

    {{-- ✅ Create Appointment for Student --}}
    <a href="{{ route('admin.appointments.create') }}">
        <button>➕ Make Appointment for Student</button>
    </a>

    <br><br>

    {{-- ✅ View Appointments --}}
    <a href="{{ route('admin.appointments.index') }}">
        <button>📋 View Appointments</button>
    </a>

    <hr>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
