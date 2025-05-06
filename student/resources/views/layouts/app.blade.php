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
       
        header {
            background-color: #17224D;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #17224D !important;
            transition: width 0.3s;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar ul {
            padding: 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 10px;
            color: white !important;
            text-decoration: none;
        }

        .sidebar .nav-link img {
            width: 30px;
            margin-right: 10px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

     
        .toggle-btn {
            background-color: #17224D !important;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 18px;
        }

       
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }

        .collapsed + .main-content {
            margin-left: 80px;
        }

        .notification-bell img {
            width: 20px; 
            height: 20px;
        }
    </style>
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <button id="toggleSidebar" class="toggle-btn">☰</button>

        @auth
            <div class="d-flex align-items-center">
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
        <nav id="sidebar" class="sidebar p-3 border-end">
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

    
        <main id="main-content" class="main-content p-4 flex-grow-1">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('main-content').classList.toggle('collapsed');
        });
    </script>

    @stack('scripts') 
</body>
</html>