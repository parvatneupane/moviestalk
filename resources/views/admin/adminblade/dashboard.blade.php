@extends('admin.adminlayout')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/dashboard.css') }}">
</head>
<body>
@section('content')

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
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-menu">
                    <div class="notification">
                        <i class="fas fa-bell"></i>
                        <div class="notification-badge">3</div>
                    </div>
                    <div class="user-profile">
                        <div class="user-avatar">AM</div>
                        <div>
                            <div>Admin Manager</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h1>Dashboard Overview</h1>
                <p>Welcome back! Here's what's happening with MovieTalk today.</p>
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
                            <i class="fas fa-arrow-up"></i> 12.5% from last week
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
                            <i class="fas fa-arrow-up"></i> 3.2% from last week
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
