<article class="movie-card">
    <div class="card-image">
<img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }} poster">

        @if(!empty($badge))
            <span class="card-badge">{{ $badge }}</span>
        @endif
    </div>
    <div class="card-content">
        <h3 class="card-title">{{ $movie->title }}</h3>
        <div class="card-meta">
            <span>{{ $movie->release_year }} </span>
            <span class="card-rating">
                <i class="fas fa-star" aria-hidden="true"></i> {{ $movie->rating }}
            </span>
        </div>
        <p class="card-description">{{ Str::limit($movie->description, 100) }}</p>
        <div class="card-actions">
            <a href="{{ route('movie.detail', $movie->id) }}">
                <i class="fas fa-play"></i> Watch
            </a>
            <a href="{{ route('movie.detail', $movie->id) }}">
                <i class="fas fa-info-circle"></i> Details
            </a>
        </div>
    </div>
</article>
