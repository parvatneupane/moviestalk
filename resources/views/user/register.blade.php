
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - MovieTalks</title>
    
    <!-- External Resources -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/userlogin.css') }}" />
</head>
<body>
    <div class="main-content">
        <div class="login-info">
            <h1>Join MovieTalks Today!</h1>
            <p>Create your account to get personalized movie recommendations, build your watchlist, and connect with other fans.</p>
            
            <ul class="features">
                <li><i class="fas fa-heart"></i><span>Save your favorite movies and shows</span></li>
                <li><i class="fas fa-bell"></i><span>Get notified about new releases</span></li>
            </ul>
        </div>
        
        <!-- Register Form Section -->
        <div class="login-form-container">
            <div class="login-box">

                <!-- Back to Home -->
                <div class="back-home">
                    <p><a href="{{ route('home') }}" aria-label="Back to Home"><i class="fa-solid fa-arrow-left fa"></i></a></p>
                </div>

                <!-- Logo / App Name -->
                <div class="logo text-center mb-3">
                    <h3 style="font-weight: bold; color: blue;">MovieTalks</h3>
                </div>

                <h2>Create Account</h2>

                <!-- Display success/error session messages -->
                @if(session('success'))
                    <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user.register')}}">
                    @csrf

                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required />
                        <i class="fas fa-user"></i>
                        @error('name')
                            <p class="error-message" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required />
                        <i class="fas fa-envelope"></i>
                        @error('email')
                            <p class="error-message" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required />
                        <i class="fas fa-lock"></i>
                        @error('password')
                            <p class="error-message" style="color: red; font-size: 0.9em;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required />
                        <i class="fas fa-lock"></i>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="login-button">Sign Up</button>

                    <!-- Separator -->
                    <div class="separator">
                        <span>Or continue with</span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-login">
                        <a href="#" class="social-btn">
                            <i class="fab fa-google"></i>
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="signup-link">
                        Already have an account? <a href="/user/login">Sign In</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>
