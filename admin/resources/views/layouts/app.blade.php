<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            background-color: #17224D;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .container-layout {
            display: flex;
            flex-grow: 1;
        }

        .sidebar {
            width: 230px; 
            background-color: #17224D;
            color: white;
            height: 100vh;
            padding: 15px; 
            flex-shrink: 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-bottom: 12px; 
        }

        .sidebar .nav-link i {
            font-size: 18px;
            margin-right: 8px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            min-width: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1></h1>
        <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </header>

    <div class="container-layout">
        <nav class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('schedules.index') }}">
                        <i class="bi bi-calendar3"></i> Schedule Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.appointments.create') }}">
                        <i class="bi bi-plus-circle"></i> Make Appointment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.appointments.index') }}">
                        <i class="bi bi-list-check"></i> View Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.students.index') }}">
                        <i class="bi bi-people"></i> Student Accounts
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts') 
</body>
</html>