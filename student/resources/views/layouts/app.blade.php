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
        body{
        background: url("{{ asset('src/xu.png') }}") no-repeat center center fixed;
        background-size: cover;
        background-size: cover !important;
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
        background-color: rgba(40, 57, 113, 0.3); 
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 10px;
        color: #ffffff; 
    }

    .login-box p {
        margin-bottom: 30px;
        color: #dddddd; 
    }

    .login-box input {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 16px;
        background-color: #ffffe0; 
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

    .error {
        color: #ff6b6b;
        margin-bottom: 20px;
    }
    </style>

</head>
<body>
   
        @auth
            <div class="d-flex justify-content-between align-items-center">
                
                

                
                <form action="{{ route('logout') }}" method="POST" class="ms-3">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                </form>

                @include('components.notification-bell')
            </div>
        @endauth
    </header>



    <main class="p-4">
        @yield('content')
    </main>

    <!-- Bootstrap JS (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts') 
</body>
</html>
