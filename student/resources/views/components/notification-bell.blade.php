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


</head>
<body>

    <header>
        @auth
            <div class="d-flex align-items-center ms-auto">
                <div style="position: relative;" id="notification-wrapper" class="me-3">
                    <button id="notificationBell" style="background: none; border: none; position: relative;">
                        <img src="{{ asset('icons/Bell2.png') }}" alt="Notifications">
                        <span id="notificationCount" class="badge bg-danger" style="position: absolute; top: -5px; right: -10px; font-size: 12px;"></span>
                    </button>

                    <div id="notificationDropdown" style="display: none; position: absolute; top: 30px; right: 0; background: white; border: 1px solid #ccc; width: 300px; z-index: 1000;">
                        <ul id="notificationList" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;"></ul>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
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


                            if (!n.is_read) {
                                item.classList.add('unread-notification'); // Highlight unread notifications
                                unreadCount++;
                            }

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
            setInterval(loadNotifications, 30000); // Refresh notifications every 30 seconds
        });
    </script>

    @stack('scripts') 
</body>
</html>