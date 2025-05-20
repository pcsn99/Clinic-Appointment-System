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

    <style>
        
        header {
            background-color: #17224D;
            color: white;
            padding: 8px 15px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 45px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        #notificationBell {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            font-size: 18px;
            position: relative;
            color: white;
        }

        #notificationBell i {
            font-size: 18px;
        }

        #notificationCount {
            position: absolute;
            top: -5px;
            right: -10px;
            font-size: 12px;
        }

        #notificationDropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            width: 300px;
            z-index: 1000;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            padding: 5px 12px;
            font-size: 14px;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #17224D;
            padding: 20px;
            top: 45px;
            color: white;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link i {
            font-size: 24px;
            margin-right: 10px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            margin-left: 250px;
            padding: 65px 20px 20px;
        }
        
    </style>
</head>
<body>
    @auth
    <header>
        <div class="nav-buttons">
            <!-- Notification Bell -->
            <div style="position: relative;">
                <button id="notificationBell">
                    <i class="bi bi-bell"></i>
                    <span id="notificationCount" class="badge bg-danger"></span>
                </button>

                <div id="notificationDropdown">
                    <ul id="notificationList" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;"></ul>
                </div>
            </div>

            <!-- Logout -->
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('student.appointments.index') }}">
                        <i class="bi bi-calendar-plus"></i> Book Appointment
                    </a>
                </li>
            </ul>
        </nav>

        

        <main class="main-content flex-grow-1">
            @yield('content')
            


            
        </main>
    </div>
    @else
    <main class="main-content">
        @yield('content')
    </main>
    @endauth

    <!-- Notification Script -->
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
                                item.classList.add('bg-primary-subtle');
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
            setInterval(loadNotifications, 30000);
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') 
</body>
</html>
