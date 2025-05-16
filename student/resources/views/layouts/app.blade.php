<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- All styles moved to app.css -->
</head>
<body>
    @auth
    <header class="auth-header">
        <h1 class="me-auto">Student Clinic Scheduler</h1>
        <div class="nav-buttons">
            <button id="notificationBell">
                <i class="bi bi-bell"></i> <!-- Bootstrap icon used -->
                <span id="notificationCount" class="badge bg-danger"></span>
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <div class="d-flex">
        <nav class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">
                        <i class="bi bi-person"></i> Account
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content flex-grow-1">
            @yield('content')
        </main>
    </div>
    @else
    <div>
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notificationDropdown');
            const list = document.getElementById('notificationList');
            const countBadge = document.getElementById('notificationCount');

            bell.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });

            function loadNotifications() {
                fetch('/notifications')
                    .then(response => response.json())
                    .then(data => {
                        list.innerHTML = '';
                        let unreadCount = 0;

                        data.forEach(n => {
                            const item = document.createElement('li');
                            item.className = 'list-group-item';
                            item.innerHTML = `<strong>${n.title}</strong><br><small>${n.message}</small>`;
                            if (!n.is_read) unreadCount++;
                            item.addEventListener('click', () => {
                                fetch(`/notifications/${n.id}/read`, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                                }).then(() => loadNotifications());
                            });
                            list.appendChild(item);
                        });

                        countBadge.textContent = unreadCount > 0 ? unreadCount : '';
                    });
            }

            loadNotifications();
            setInterval(loadNotifications, 30000); 
        });
    </script>

    @stack('scripts') 
</body>
</html>