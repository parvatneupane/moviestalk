@extends('layouts.app')

@section('title', 'User Profile')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

@section('content')
<!-- Main Profile Content -->
<div class="container profile-container">
    <!-- User Information Card -->
    <div class="profile-card">
        <div class="profile-header">
            <img id="avatarPreview" 
                 src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : 'https://www.gravatar.com/avatar/' . md5(auth()->user()->email) }}" 
                 alt="User Avatar" 
                 class="avatar">
            <div class="user-info">
                <h1>{{ auth()->user()->name }}</h1>
                <span class="badge">Movietalk Member</span>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-item">
                <i class="fas fa-envelope"></i>
                <span>{{ auth()->user()->email }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>Joined: {{ auth()->user()->created_at->format('M Y') }}</span>
            </div>

                <div class="detail-item">
                    <i class="fas fa-heart"></i>
                    <span>Movie Lover</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-users"></i>
                    <span>Movie Talk Army</span>
                </div>
        </div>
    </div>
    
    <!-- Combined Profile Update + Password Change Card -->
    <div class="profile-card" style="margin-top: 20px;">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" data-tab="update"><i class="fas fa-user-edit"></i> Update Profile</div>
            <div class="tab" data-tab="password"><i class="fas fa-key"></i> Change Password</div>
        </div>
        
        <!-- Update Profile Form -->
        <div class="tab-content active" id="update-tab">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}" required>
                </div>

                <div class="form-group">
                    <label for="avatar"><i class="fas fa-image"></i> Avatar</label>
                    <input type="file" name="avatar" id="avatar" class="form-control" onchange="previewAvatar(event)">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="tab-content" id="password-tab">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password"><i class="fas fa-lock"></i> Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password"><i class="fas fa-key"></i> New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation"><i class="fas fa-check-circle"></i> Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Change Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const preview = document.getElementById('avatarPreview');
        preview.src = URL.createObjectURL(event.target.files[0]);
    }

    // Tab switching
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab");
        const contents = document.querySelectorAll(".tab-content");

        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                // remove active
                tabs.forEach(t => t.classList.remove("active"));
                contents.forEach(c => c.classList.remove("active"));

                // add active to selected
                tab.classList.add("active");
                document.getElementById(tab.dataset.tab + "-tab").classList.add("active");
            });
        });
    });
</script>
@endpush
