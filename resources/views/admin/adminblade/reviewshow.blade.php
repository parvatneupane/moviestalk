
@extends('layouts.admin')
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/reviews.css') }}">
</head>

@section('content')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #f72585;
        --bg-color: #f5f7fb;
        --card-bg: #fff;
        --text-color: #343a40;
        --subtext-color: #6c757d;
        --border-radius: 12px;
        --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .reviews-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
    }

    .reviews-card:hover {
        box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }

    .card-header {
        background: linear-gradient(135deg, #2c2c55, #1a1a3d);
        color: #fff;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
    }

    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
    }

    .header-center {
        text-align: center;
    }

    .header-center .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .header-center .stat-label {
        font-size: 1rem;
        opacity: 0.85;
    }

    .back-btn {
        background-color: rgba(255, 255, 255, 0.25);
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        color: #fff;
        transition: var(--transition);
    }

    .back-btn:hover {
        background-color: rgba(255, 255, 255, 0.45);
        transform: translateY(-2px);
    }

    .reviews-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .reviews-table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-color: #edf2f9;
    }

    .reviews-table tbody tr {
        transition: var(--transition);
    }

    .reviews-table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        margin-right: 12px;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .rating-badge {
        background: linear-gradient(135deg, #4cc9f0, #4361ee);
        color: #fff;
        padding: 0.45rem 0.9rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .review-text {
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--subtext-color);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #555;
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .header-title, .header-center {
            text-align: center;
        }
        .user-info {
            flex-direction: column;
            align-items: flex-start;
        }
        .user-avatar {
            margin-bottom: 8px;
        }
        .review-text {
            max-width: 200px;
        }
    }
</style>

@section('content')
<div class="admin-container">
    <div class="reviews-card">
        <!-- Header -->
        <div class="card-header">
            <div class="header-title">{{ $movie->title }}</div>

            <div class="header-center">
                <div>All Reviews</div>
                <div class="stat-value">{{ $reviews->count() }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>

            <div>
                <a href="{{ url('/admin/movies') }}" class="btn back-btn">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="table-responsive p-3">
            <table class="table reviews-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Posted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($review->user->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $review->user->name ?? 'Unknown User' }}</div>
                                        <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="rating-badge">{{ $review->rating ?? '-' }}/5</span></td>
                            <td><div class="review-text">{{ $review->review }}</div></td>
                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-comment-slash"></i>
                                    <h4 class="h5">No reviews yet</h4>
                                    <p>This movie hasn't received any reviews yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
