<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <header>
        <h1>Clinic Admin</h1>
        @auth('admin')
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
