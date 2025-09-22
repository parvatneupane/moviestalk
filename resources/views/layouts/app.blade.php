<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'MovieTalks')</title>

  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/layout.css')}}">
    @stack('styles')
  
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="{{ route('home') }}" class="logo">
                    <i class="fas fa-film"></i>
                    Movie<span>Talks</span>
                </a>

                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('movies') }}" class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">Movies</a></li>
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a></li>
                </ul>

                <form action="{{ route('movies.search') }}" method="GET" class="search-bar">
    <i class="fas fa-search"></i>
    <input 
        type="text" 
        name="query" 
        placeholder="Search movies..." 
        id="search-input" 
        required
    />
</form>

                
                <div class="user-actions">
                    @auth
                    <div class="notification-wrapper">
                        <i id="notificationBell" class="fas fa-bell notification-icon"></i>

                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span id="notificationCount" class="notification-badge">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif

                        <div id="notificationDropdown" class="notification-dropdown">
                            @forelse(auth()->user()->notifications as $notification)
                                <div class="notification-item" data-id="{{ $notification->id }}">
                                    <span>{{ $notification->data['message'] }}</span>
                                    <i class="fas fa-trash-alt delete-notification" title="Delete"></i>
                                </div>
                            @empty
                                <div class="notification-item" style="text-align: center; color: #999;">
                                    No notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="user-dropdown">
                        <button class="user-icon p-0 overflow-hidden border-0">
                            <img src="{{ auth()->user()->avatar 
                                ? asset('storage/' . auth()->user()->avatar) 
                                : asset('images/default-avatar.png') }}" 
                                alt="User Avatar"
                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('profile') }}">Profile</a>
                            <a href="{{ route('mylist') }}">My Watchlist</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="auth-links">
                        <a href="{{ route('user.login.form') }}">Login</a>
                    </div>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- Company Info -->
                <div class="footer-column">
                    <div class="footer-logo">Movie<span>Talks</span></div>
                    <p class="footer-about">Your ultimate destination for movie reviews, recommendations, and entertainment news. Discover your next favorite film with us.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="{{ route('movies') }}"><i class="fas fa-chevron-right"></i> Movies</a></li>
                        
                    </ul>
                </div>
              
   


                <!-- Support -->
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                        
                        
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('2025') }} Movie Talks. All Rights Reserved.</p>
                <p>Made with <i class="fas fa-heart" style="color: var(--accent);"></i> for movie lovers</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // ========================
    // Toggle Notification Dropdown
    // ========================
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');

    if (bell && dropdown) {
        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && e.target !== bell) {
                dropdown.style.display = 'none';
            }
        });
    }

    // ========================
    // Delete Individual Notifications (Optional AJAX)
    // ========================
    document.querySelectorAll('.delete-notification').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const notificationItem = this.closest('.notification-item');
            const id = notificationItem.dataset.id;

            // Optional: AJAX call to delete on the backend
            fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    notificationItem.remove();

                    // Optionally update badge count
                    const badge = document.getElementById('notificationCount');
                    if (badge) {
                        const count = document.querySelectorAll('.notification-item').length;
                        if (count <= 1) {
                            badge.style.display = 'none';
                        } else {
                            badge.textContent = count - 1;
                        }
                    }
                } else {
                    alert('Failed to delete notification.');
                }
            }).catch(error => {
                console.error(error);
                alert('Error deleting notification.');
            });
        });
    });

    // ========================
    // Toggle User Dropdown
    // ========================
    const userIconBtn = document.querySelector('.user-icon');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (userIconBtn && dropdownContent) {
        userIconBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownContent.contains(e.target) && e.target !== userIconBtn) {
                dropdownContent.classList.remove('show');
            }
        });
    }

    // ========================
    // Mobile Menu Toggle
    // ========================
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.querySelector('.nav-links');
    const searchBar = document.querySelector('.search-bar');
    const userActions = document.querySelector('.user-actions');

    if (mobileBtn) {
        mobileBtn.addEventListener('click', function () {
            mobileBtn.classList.toggle('active');
            navLinks && navLinks.classList.toggle('mobile-open');
            searchBar && searchBar.classList.toggle('mobile-open');
            userActions && userActions.classList.toggle('mobile-open');
        });
    }

    // ========================
    // Optional: Close mobile menu on link click
    // ========================
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks && navLinks.classList.contains('mobile-open')) {
                navLinks.classList.remove('mobile-open');
                mobileBtn.classList.remove('active');
                searchBar && searchBar.classList.remove('mobile-open');
                userActions && userActions.classList.remove('mobile-open');
            }
        });
    });
});
</script>
</div>
</body>
</html>