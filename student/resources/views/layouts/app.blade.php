<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>

    <!-- Bootstrap CSS (v5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Top Bar */
        header {
            background-color: #17224D;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Sidebar (Resized to 200px width) */
        .sidebar {
            width: 300px; /* Updated width */
            height: 100vh;
            position: fixed;
            background-color: #E0E7F1;
            overflow-y: auto;
            padding: 20px;
        }

        .sidebar ul {
            padding: 0;
            list-style-type: none;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 15px;
            background-color: #17224D; /* Dark blue buttons */
            border-radius: 20px;
            margin-bottom: 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            width: 100%;
        }

        .sidebar .nav-link img {
            width: 30px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link span {
            color: white;
        }

        /* Adjust Main Content Position */
        .main-content {
            margin-left: 200px; /* Match sidebar width */
            padding: 20px;
        }

        /* Notification Bell */
        .notification-bell img {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <header>
        @auth
            <div class="d-flex align-items-center ms-auto">
                <a class="nav-link notification-bell" href="#">
                    <img src="{{ asset('icons/Bell2.png') }}" alt="Notifications">
                </a>
                <form action="{{ route('logout') }}" method="POST" class="ms-3">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                </form>
            </div>
        @endauth
    </header>

    <div class="d-flex">
        @auth
        <!-- Sidebar (Fixed, Now 200px Wide) -->
        <nav class="sidebar p-3 border-end">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('icons/Dashboard.png') }}" alt="Dashboard"> 
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('icons/Profile.png') }}" alt="Account"> 
                        <span>Account</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('icons/print.png') }}" alt="Print Form"> 
                        <span>Print Form</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endauth

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts') 
</body>
</html>