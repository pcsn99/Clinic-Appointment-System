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
        
        .sidebar {
            width: 280px;
            background-color: #17224D;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 18px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .sidebar .nav-link i {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .main-content {
            padding: 20px;
            margin-left: 20px;
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

    <div class="d-flex">
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

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts') 
</body>
</html>