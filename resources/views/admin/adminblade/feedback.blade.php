@extends('layouts.admin')

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/feedback.css') }}">


@section('content')

    <div class="container">

        <!-- Main Content -->
        <div class="main">

            <!-- Header -->
            <div class="header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
    <!-- Dashboard title -->
            <div class="dashboard-title">
                <h1>Feedback Management</h1>
                <p>View and manage user feedback</p>
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

        

            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon icon-total">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-value">{{ $feedbacks->total() }}</h3>
                        <p class="stat-label">Total Feedback</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-replied">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-value">{{ $feedbacks->where('status','replied')->count() }}</h3>
                        <p class="stat-label">Replied</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-value">{{ $feedbacks->where('status','pending')->count() }}</h3>
                        <p class="stat-label">Pending</p>
                    </div>
                </div>
            </div>


            <!-- Feedback Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Submitted At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedbacks as $feedback)
                                <tr>
                                    <td>{{ $feedback->name ?? $feedback->user->name }}</td>
                                    <td>{{ $feedback->email ?? $feedback->user->email }}</td>
                                    <td>{{ $feedback->subject ?? '-' }}</td>
                                    <td class="message-cell">{{ Str::limit($feedback->message, 60) }}</td>
                                    <td>{{ $feedback->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($feedback->status === 'replied')
                                            <span class="badge bg-success">Replied</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/feedbacks/' . $feedback->id) }}" class="btn btn-view btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <form action="{{ url('admin/deletefeedbacks/' . $feedback->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this feedback?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No feedback submitted yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $feedbacks->links() }}
                </div>
            </div>

        </div>
    </div>

@endsection
