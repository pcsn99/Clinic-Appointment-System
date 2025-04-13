
<div style="position: relative;" id="notification-wrapper">
    <button id="notificationBell" style="background: none; border: none; position: relative;">
        🔔
        <span id="notificationCount" class="badge bg-danger" style="position: absolute; top: -5px; right: -10px; font-size: 12px;"></span>
    </button>

    <div id="notificationDropdown" style="display: none; position: absolute; top: 30px; right: 0; background: white; border: 1px solid #ccc; width: 300px; z-index: 1000;">
        <ul id="notificationList" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;"></ul>
    </div>
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
        setInterval(loadNotifications, 30000); // refresh every 30s
    });
</script>
