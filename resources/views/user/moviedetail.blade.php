@extends('layouts.app')

@section('title', $movie->title . ' - MovieTalks')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/moviedetail.css') }}">
@endpush

@section('content')
<!-- Movie Hero Section -->
<section class="movie-hero">
    <div class="container">
        <div class="movie-hero-content">
            <!-- Movie trailer -->
            <div class="movie-trailer">
                @if($movie->trailer_url)
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; height: auto;">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::after($movie->trailer_url, 'v=') }}" 
                        title="{{ $movie->title }} Official Trailer" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                    </iframe>
                </div>
                @else
                <img src="{{ asset('images/default-movie-poster.jpg') }}" alt="No trailer available">
                @endif
            </div>

            <div class="movie-info">
                <h1 class="movie-title">{{ $movie->title }}</h1>
                <div class="movie-meta">
                    <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                    <span><i class="fas fa-clock"></i> {{ $movie->runtime ?? 'N/A' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_date ?? 'N/A' }}</span>
                    <span><i class="fas fa-film"></i> {{ $movie->category->name ?? 'N/A' }}</span>
                </div>

                <div class="movie-actions">
                    @auth
                        @if($inWatchlist)
                        <button class="btn btn-watchlist added">
                            <i class="fas fa-check"></i> In Watchlist
                        </button>
                        @else
                        <form action="{{ route('movie.toggle-watchlist', $movie->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-watchlist">
                                <i class="fas fa-plus"></i> Add to Watchlist
                            </button>
                        </form>
                        @endif
                    @else
                        <a href="{{ route('user.login.form') }}" class="btn btn-watchlist">
                            <i class="fas fa-plus"></i> Add to Watchlist
                        </a>
                    @endauth
                </div>

                <p class="movie-description">{{ $movie->description }}</p>
            </div>
        </div>

        <!-- Movie Details -->
        <div class="movieinfo">
            <div class="movie-details-grid">
                <div class="detail-item"><span class="detail-label">Director:</span> <span class="detail-value">{{ $movie->director ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Writers:</span> <span class="detail-value">{{ $movie->writer ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Stars:</span> <span class="detail-value">{{ $movie->cast ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Genres:</span> <span class="detail-value">{{ $movie->genres ?? $movie->category->name }}</span></div>
                <div class="detail-item"><span class="detail-label">Release Date:</span> <span class="detail-value">{{ $movie->release_date ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Production:</span> <span class="detail-value">{{ $movie->production ?? 'N/A' }}</span></div>
            </div>
        </div>

        @auth
        <!-- Ratings Section -->
        <div class="rating-container">
            <div class="user-rating">
    <h3>Your Rating</h3>
    <form id="rating-form" action="{{ url('movierating/' . $movie->id ) }}" method="POST">
        @csrf
        <div class="star-rating">
            @for($i=1; $i<=5; $i++)
                <label>
                    <input type="radio" name="rating" value="{{ $i }}"
                        {{ (!empty($userrating) && $userrating == $i) ? 'checked' : '' }}> 
                    <i class="fas fa-star"></i>
                </label>
            @endfor
        </div>
        <button type="submit" class="rating-submit">Submit Rating</button>
    </form>
</div>


            <div class="rating-stars">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= floor($rating))
            <i class="fas fa-star"></i> <!-- Full star -->
        @elseif($i - 0.5 <= $rating)
            <i class="fas fa-star-half-alt"></i> <!-- Half star -->
        @else
            <i class="far fa-star"></i> <!-- Empty star -->
        @endif
    @endfor
</div>

        </div>
        @endauth
    </div>
</section>

<main class="container">
    <!-- Reviews Section -->
    <section class="reviews-section">
        <h2 class="section-title">User Reviews</h2>

        @auth
        <div class="review-form">
            <form method="POST" action="{{ route('movie.submit-review', $movie->id) }}">
                @csrf
                <input type="text" name="title" placeholder="Review title" required>
                <textarea name="content" placeholder="Write your review..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
        @else
        <p>Please <a href="{{ route('user.login.form') }}">login</a> to write a review.</p>
        @endauth

        {{-- <div class="reviews-list">
            @forelse($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <strong>{{ $review->user->name }}</strong>
                    <span>{{ $review->created_at->format('F j, Y') }}</span>
                </div>
                <div class="review-content">
                    <p>{{ $review->content }}</p>
                </div>
            </div>
            @empty
            <p>No reviews yet.</p>
            @endforelse
        </div> --}}
    </section>

    <!-- Similar Movies -->
    <section class="similar-movies">
        <h2 class="section-title">You Might Also Like</h2>
        <div class="movies-grid">
            @foreach($similarMovies as $similar)
            <div class="movie-card">
                <a href="{{ route('movie.detail', $similar->id) }}">
                    <img src="{{ asset('storage/' . $similar->poster) }}" alt="{{ $similar->title }}">
                    <h3>{{ $similar->title }}</h3>
                    <span>{{ $similar->release_date }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/moviedetail.js') }}"></script>
@endpush
