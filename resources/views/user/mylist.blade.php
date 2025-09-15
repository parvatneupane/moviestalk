@extends('layouts.app')

@section('title', 'My List - MovieTalks')

<link rel="stylesheet" href="{{ asset('css/mylist.css') }}">
@section('content')
<main>
    <div class="container">
        <section class="content-header">
            <div class="section-header">
                <h2 class="section-title">My Saved Content</h2>
                <div class="sort-group">
                    <label for="sort">Sort By:</label>
                    <select class="filter-select" id="sort">
                        <option value="recent">Recently Added</option>
                        <option value="rating">Highest Rating</option>
                        <option value="title">Title</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Items</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['watched'] }}</div>
                    <div class="stat-label">Watched</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['unwatched'] }}</div>
                    <div class="stat-label">Unwatched</div>
                </div>
            </div>
        </section>

        <section class="movies-grid-section">
            <div class="movies-grid">
                @forelse($watchlist as $item)
                    <div class="movie-card" data-watched="{{ $item->watched ? 'true' : 'false' }}">
                        <div class="card-image">
                            <img src="{{ asset('storage/' . $item->movie->poster) }}" alt="{{ $item->movie->title }}">
                            @if($item->watched)
                                <span class="card-badge">Watched</span>
                            @endif
                            <div class="card-actions-overlay">
                                <button class="watched-toggle" data-id="{{ $item->id }}" data-watched="{{ $item->watched ? 'true' : 'false' }}">
                                    <i class="{{ $item->watched ? 'fas fa-check-circle' : 'far fa-check-circle' }}"></i>
                                </button>
                                <div class="remove-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">{{ $item->movie->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $item->movie->release_year }} • {{ $item->movie->category->name }}</span>
                                <span class="card-rating">
                                    <i class="fas fa-star"></i> {{ number_format($item->movie->rating, 1) }}
                                </span>
                            </div>
                            <p class="card-description">{{ Str::limit($item->movie->description, 100) }}</p>

                            <!-- Progress Bar -->
                            <div class="progress-container">
                                <span>Progress: {{ $item->progress }}%</span>
                                <div class="progress-bar" data-id="{{ $item->id }}" data-progress="{{ $item->progress }}">
                                    <div class="progress" style="width: {{ $item->progress }}%;"></div>
                                </div>
                            </div>

                            <div class="card-actions">
                                <!-- Added continue-btn class -->
                                <a href="{{ route('movie.detail', $item->movie->id) }}" class="continue-btn">
                                    <i class="fas fa-play"></i> Continue
                                </a>
                                <a href="{{ route('movie.detail', $item->movie->id) }}">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-bookmark"></i>
                        <h3>Your watchlist is empty</h3>
                        <p>Start adding movies to your watchlist to keep track of what you want to watch.</p>
                        <a href="{{ route('movies') }}" class="btn btn-primary">Browse Movies</a>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/mylist.js') }}"></script>
@endpush
