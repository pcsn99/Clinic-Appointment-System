<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

     .login-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 60px;
    }

    .login-box {
        background-color: rgba(180, 208, 252, 0.85);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 10px;
        color: #333;
    }

    .login-box p {
        margin-bottom: 30px;
        color: #555;
    }

    .login-box input {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 16px;
    }

    .btn {
        background-color: #1f3c88;
        color: white;
        border: none;
        padding: 12px;
        width: 100%;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #162c63;
    }

    .bottom-text {
        margin-top: 15px;
    }

    .bottom-text a {
        color: #1f3c88;
        text-decoration: none;
    }

    .bottom-text a:hover {
        text-decoration: underline;
    }
        
        header {
            background-color: #17224D;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .sidebar {
            width: 300px;
            height: 100vh;
            position: fixed;
            background-color: #E0E7F1;
            overflow-y: auto;
            padding: 20px;
            top: 60px; 
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
            background-color: #17224D;
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

        
        .main-content {
            margin-left: 300px; 
            padding: 80px 20px 20px; 
        }

      
        .notification-bell img {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body style="@hasSection('body_background') background: @yield('body_background'); background-size: cover; @endif">

<body>
 
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