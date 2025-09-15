<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'MovieTalks')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
 <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
<style>
    :root {
    --dark-bg: #0c0c14;
    --darker-bg: #070710;
    --card-bg: #161626;
    --card-hover: #1d1d33;
    --accent: #ff2a6d;
    --accent-hover: #ff4d85;
    --secondary: #05d9e8;
    --text-primary: #ffffff;
    --text-secondary: #b3b3cc;
    --border: #2a2a3a;
    --rating: #ffc107;
    --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* ========== BASE STYLES ========== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, var(--dark-bg) 0%, var(--darker-bg) 100%);
    color: var(--text-primary);
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    overflow-x: hidden;
}

h1, h2, h3, h4 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
}

a {
    text-decoration: none;
    color: var(--text-primary);
    transition: var(--transition);
}

button {
    cursor: pointer;
    border: none;
    font-weight: 600;
    transition: var(--transition);
}

.container {
    width: 98%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

section {
    padding: 80px 0;
}

/* ========== BUTTON STYLES ========== */
.btn-primary {
    background: linear-gradient(90deg, var(--accent) 0%, #ff5e94 100%);
    color: white;
    padding: 12px 28px;
    border-radius: 50px;
    font-size: 1rem;
    box-shadow: 0 5px 15px rgba(255, 42, 109, 0.4);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 42, 109, 0.6);
}

.btn-secondary {
    background: rgba(248, 243, 243, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    padding: 10px 25px;
    border-radius: 50px;
    font-size: 0.95rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(240, 236, 236, 0.2);
    transform: translateY(-3px);
}

/* ========== HEADER & NAVIGATION ========== */
header {
    background: rgba(7, 7, 16, 0.9);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    backdrop-filter: blur(10px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    transition: var(--transition);
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
}

.logo span {
    color: var(--accent);
}

.logo i {
    font-size: 32px;
    color: var(--secondary);
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 30px;
}

.nav-links li a {
    font-weight: 500;
    position: relative;
    padding: 8px 5px;
}

.nav-links li a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 3px;
    bottom: 0;
    left: 0;
    background: var(--accent);
    border-radius: 2px;
    transition: var(--transition);
}

.nav-links li a:hover::after,
.nav-links li a.active::after {
    width: 100%;
}

.nav-links li a.active {
    color: var(--accent);
}

.search-bar {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50px;
    padding: 8px 15px;
    width: 240px;
    transition: var(--transition);
}

.search-bar:hover {
    background: rgba(255, 255, 255, 0.12);
}

.search-bar input {
    background: transparent;
    border: none;
    color: var(--text-primary);
    width: 100%;
    padding: 5px 10px;
    outline: none;
    font-size: 0.95rem;
}

.search-bar i {
    color: var(--text-secondary);
    font-size: 1rem;
}


/* User dropdown */
.user-dropdown {
    position: relative;
}

.user-icon {
    background: rgba(255, 255, 255, 0.08);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.user-icon:hover {
    background: var(--accent-hover);
    transform: scale(1.1);
}

.dropdown-content {
    position: absolute;
    top: 50px;
    right: 0;
    background: var(--card-bg);
    border-radius: 8px;
    padding: 10px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1000;
    min-width: 150px;
}

.dropdown-content.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-content a,
.dropdown-content button {
    display: block;
    width: 100%;
    background: none;
    border: none;
    color: var(--text-primary);
    padding: 12px 20px;
    text-align: left;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    font-size: 0.9rem;
}

.dropdown-content a:hover,
.dropdown-content button:hover {
    background: var(--accent);
}



footer {
    background: var(--darker-bg);
    padding: 60px 0 30px;
    position: relative;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-column h3 {
    font-size: 1.4rem;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 10px;
}

.footer-column h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: var(--accent);
}

.footer-logo {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-primary);
}

.footer-logo span {
    color: var(--accent);
}

.footer-about {
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.6;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--card-bg);
    font-size: 1.1rem;
    transition: var(--transition);
}

.social-links a:hover {
    background: var(--accent);
    transform: translateY(-5px);
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 15px;
}

.footer-links a {
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-links a:hover {
    color: var(--accent);
    transform: translateX(5px);
}

.footer-links a i {
    color: var(--secondary);
    font-size: 0.9rem;
}

.footer-bottom {
    text-align: center;
    padding: 25px 0;
    border-top: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.footer-bottom p {
    margin-bottom: 10px;
}

/* ========== RESPONSIVE DESIGN ========== */
@media (max-width: 1024px) {
    .slide-content h2 {
        font-size: 2.5rem;
    }
    
    .slide-content p {
        font-size: 1.1rem;
    }
}

@media (max-width: 900px) {
    .nav-links, .search-bar, .user-actions {
        display: none;
    }
    
    .mobile-menu-btn {
        display: block;
    }
    
    .video-slider-container {
        height: 60vh;
    }
    
    .slide-content {
        padding: 20px;
    }
    
    .slide-content h2 {
        font-size: 2rem;
    }
    
    .slide-content p {
        font-size: 1rem;
    }
    
    .slide-meta {
        gap: 10px;
    }
    
    .slide-buttons {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .slider-arrows {
        display: none;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
}

@media (max-width: 600px) {
    .video-slider-container {
        height: 50vh;
    }
    
    .slide-content h2 {
        font-size: 1.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .movies-grid {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .footer-content {
        grid-template-columns: 1fr;
    }
}



 .profile-container {
            display: flex;
            gap: 30px;
            margin: 40px auto;
            flex-wrap: wrap;
        }
        
        .profile-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            flex: 1;
            min-width: 300px;
            transition: transform 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .user-info h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .user-info p {
            color: var(--text-secondary);
            margin-bottom: 10px;
        }
        
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent) 0%, #c0392b 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .profile-details {
            margin: 25px 0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        
        .detail-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .detail-item i {
            width: 24px;
            color: var(--primary);
            margin-right: 15px;
            font-size: 18px;
        }


         .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 16px;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.3);
        }
        
        .btn {
            padding: 14px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #2980b9 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 25px;
        }
        
        .tab {
            padding: 12px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .tab:hover {
            color: var(--text-primary);
        }
        
        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
</style>

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

                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search movies..." id="search-input" />
                </div>
              
          @auth
        <div class="user-dropdown">
        <button class="user-icon">
            <i class="fas fa-user"></i>
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
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="{{ route('movies') }}"><i class="fas fa-chevron-right"></i> Movies</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> TV Shows</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> New Releases</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Top Rated</a></li>
                    </ul>
                </div>
              
                <div class="footer-column">
                    <h3>Categories</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Action</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Comedy</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Drama</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Sci-Fi</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Horror</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Help Center</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Terms of Service</a></li>
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
    // === User Dropdown Toggle ===
    const userIconBtn = document.querySelector('.user-icon');
    const dropdown = document.querySelector('.dropdown-content');

    if (userIconBtn && dropdown) {
        userIconBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && !userIconBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }

    // === Mobile Menu Toggle ===
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', function () {
            navLinks.classList.toggle('open');
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
            });
        });
    }
});
</script>

      