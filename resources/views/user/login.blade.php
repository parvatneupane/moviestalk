<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MovieTalks</title>
    
    <!-- External Resources -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/userlogin.css') }}">
</head>
<body>
   

    <div class="main-content">
        <div class="login-info">
            <h1>Welcome Back to MovieTalks</h1>
            <p>Sign in to access your personalized movie recommendations, watchlist, and exclusive content.</p>
            
            <ul class="features">
                <li><i class="fas fa-heart"></i><span>Save your favorite movies and shows</span></li>
                <li><i class="fas fa-bell"></i><span>Get notified about new releases</span></li>
                <li><i class="fas fa-comments"></i><span>Join the conversation with other movie lovers</span></li>
                <li><i class="fas fa-ticket-alt"></i><span>Get exclusive access to early screenings</span></li>
            </ul>
        </div>
        
        <!-- Login Form Section -->
        <div class="login-form-container">
            <div class="login-box">

                <!-- Back to Home -->
                <div class="back-home">
                    <p><a href="{{route('home')}}" aria-label="Back to Home"><i class="fa-solid fa-arrow-left fa"></i></a></p>
                </div>

                <!-- Logo / App Name -->
                <div class="logo text-center mb-3">
                    <h3 style="font-weight: bold; color: blue;">MovieTalks</h3>
                    @include('session') <!-- Include session flash messages -->
                </div>

                <h2>Sign In</h2>

                <!-- Login Form -->
                <form method="POST" action="{{ route('user.login') }}">
                    @csrf
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="/user/forgot-password" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="login-button">Sign In</button>

                    <div class="separator">
                        <span>Or continue with</span>
                    </div>

                    <div class="social-login">
                        <a href="#" class="social-btn">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>

                    <div class="signup-link">
                        Don't have an account? <a href="/user/register">Sign Up Now</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

<script>
    const dashboardUrl = "/user/dashboard";  // Use direct path for the dashboard URL
</script>

</html>
