<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('admincss/adminlayout.css') }}"> --}}

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
        }

        .container {
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            height: 100vh;
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #1a252f;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar-menu {
            padding: 15px;
        }

        .menu-label {
            font-size: 12px;
            color: #aaa;
            margin: 10px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            transition: background 0.3s;
            border-radius: 4px;
        }

        .menu-item i {
            margin-right: 10px;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #34495e;
        }

        /* Collapsed Sidebar */
        .sidebar.active {
            width: 60px;
        }

        .sidebar.active .sidebar-header h2 span,
        .sidebar.active .menu-item span,
        .sidebar.active .menu-label {
            display: none;
        }

        /* Main Content */
        main {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        .sidebar.active ~ main {
            margin-left: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-profile">
                        <div class="user-avatar"></div>
                        <div>
                             <div>{{ Auth::user()->name }}</div>
                        </div>
                    </div>
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-film"></i> <span>MovieTalk</span></h2>
                <button class="menu-toggle"><i class="fas fa-bars"></i></button>
            </div>
            <div class="sidebar-menu">
                <div class="menu-label">Main Navigation</div>
                <a href="{{url(path: '/admin/dashboard')}}" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="menu-label">Content Management</div>
                <a href="{{url(path: '/admin/users')}}" class="menu-item active">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>


                <a href="{{url(path: '/admin/movies')}}" class="menu-item active">
                    <i class="fas fa-film"></i>
                    <span>Movies</span>
                </a>

                <a href="{{url(path: '/admin/addmovies')}}" class="menu-item active">
                    <i class="fas fa-plus"></i>
                    <span>Add Movies</span>
                </a>


                <a href="{{url(path: '/admin/genres')}}" class="menu-item active">
                    <i class="fas fa-film"></i>
                    <span>Genres</span>
                </a>
                
                
                <div class="menu-label">System</div>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<a href="#" class="menu-item"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="fas fa-cog"></i>
    <span>Logout</span>
</a>

            </div>
        </div>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const sidebar = document.querySelector(".sidebar");

            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });
        });
    </script>
</body>
</html>
