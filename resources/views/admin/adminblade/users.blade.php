@extends('layouts.admin')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin - Users</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/users.css') }}">
</head>
@section('content')

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

                <div class="user-menu">
                     <div class="user-profile">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                             <div>{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management Header -->
            <div class="user-management-header">
                <div class="dashboard-title">
                    <h1>User Management</h1>
                    <p>Manage all MovieTalk users and their permissions</p>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $data)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="user-avatar-small">
                                        {{ strtoupper(substr($data->name, 0, 2)) }}
                                    </div>
                                    <div>{{ $data->name }}</div>
                                </div>
                            </td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->role }}</td>
                            <td>
                                <span class="status-badge {{ $data->status == 'Active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td>{{ $data->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ url('/admin/users/' . $data->id) }}" class="btn btn-edit btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ url('/admin/deleteusers/' . $data->id) }}" class="btn btn-delete btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</body>


    {{-- <script src="{{ asset('adminjs/users.js') }}"></script> --}}
 
@endsection