@extends('layouts.admin')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/dashboard.css') }}">
</head>

@section('content')


<body>
    <body>
    <div class="container">
        <!-- Main Content -->
        <div class="main">
            <!-- Header -->
            <div class="header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>

                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search users...">
                </div>

                <div class="notification" style="position: relative;">
    <i id="notificationBell" class="fas fa-bell" style="cursor: pointer; position: relative;">
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span id="notificationCount" class="notification-badge">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </i>

    <div id="notificationDropdown"
     style="display:none; position: absolute; right: 0; top: 30px; width: 300px; max-height: 300px; overflow-y: auto; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 5px; z-index: 999;">
    @forelse(auth()->user()->notifications as $notification)
        <div class="notification-item" style="padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;" data-id="{{ $notification->id }}">
            <span>{{ $notification->data['message'] }}</span>
            <i class="fas fa-trash-alt delete-notification" style="color: red; cursor: pointer;" title="Delete"></i>
        </div>
    @empty
        <div class="notification-item" style="padding: 10px; text-align: center; color: #999;">
            No notifications
        </div>
    @endforelse
</div>

</div>


                <div class="user-menu">
                     <div class="user-profile">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                             <div>{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h1>Dashboard Overview</h1>
                <p>Welcome back,  Here's what's happening with MovieTalk today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(92, 107, 192, 0.2); color: var(--primary);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $users }}</div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-change up">
                            <i class="fas fa-arrow-up"></i> 
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(255, 87, 34, 0.2); color: var(--secondary);">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $movies }}</div>
                        <div class="stat-label">Movies</div>
                        <div class="stat-change up">
                            <i class="fas fa-arrow-up"></i> 
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: rgba(38, 166, 154, 0.2); color: var(--accent);">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $reviews??0 }}</div>
                        <div class="stat-label">Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script src="{{ asset('adminjs/dashboard.js') }}"></script>

</body>
 @endsection
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    const count = document.getElementById('notificationCount');

    bell.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

        // Mark all notifications as read
        fetch("{{ route('admin.notifications.markAsRead') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
        })
        .then(response => {
            if (response.ok && count) count.remove();
        })
        .catch(error => console.error("Error marking notifications as read:", error));
    });

    document.addEventListener('click', function (e) {
        if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Delete notification
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();

            const notificationItem = this.closest('.notification-item');
            const notificationId = notificationItem.dataset.id;

            fetch(`/admin/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    notificationItem.remove();

                    // If no more notifications, show "No notifications" message
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        dropdown.innerHTML = `
                            <div class="notification-item" style="padding: 10px; text-align: center; color: #999;">
                                No notifications
                            </div>`;
                    }
                } else {
                    alert(data.message || 'Failed to delete notification.');
                }
            })
            .catch(error => {
                console.error("Delete failed:", error);
            });
        });
    });
});
</script>


