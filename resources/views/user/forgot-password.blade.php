@extends('layouts.app')

@section('content')
<div class="login-form-container">
    <div class="login-box">
        <h2>Forgot Your Password?</h2>
        <p>Enter your email address below and we'll send you a link to reset your password.</p>

        @if (session('status'))
            <div style="color: green; margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="color: red; margin-bottom: 1rem;">
                <ul style="list-style-type:none; padding-left:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.forgot-password.email') }}">
            @csrf
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}">
                <i class="fas fa-envelope"></i>
            </div>

            <button type="submit" class="login-button">Send Password Reset Link</button>
        </form>

        <div class="signup-link" style="margin-top: 1rem;">
            Remember your password? <a href="/user/login">Sign In</a>
        </div>
    </div>
</div>
@endsection
