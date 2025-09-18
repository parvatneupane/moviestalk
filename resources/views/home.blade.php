@extends('layouts.app')

@section('title', 'Home - MovieTalks')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">

@section('content')
    <!-- Video Slider Section -->
    <section class="video-slider-container" aria-label="Featured movie trailers">
        @foreach($featuredMovies as $movie)
            <div class="video-slide {{ $loop->first ? 'active' : '' }}">
                @if($movie->trailer_url)
                    <iframe 
                        src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::after($movie->trailer_url, 'v=') }}?autoplay=1&mute=1" 
                        frameborder="0" 
                        allow="autoplay; encrypted-media" 
                        allowfullscreen
                        aria-label="Trailer for {{ $movie->title }}"
                    ></iframe>
                @else
                    <div class="no-trailer">
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }} poster">
                        <p>No trailer available</p>
                    </div>
                @endif

                <div class="slide-content">
                    <div class="container">
                        <h2>{{ $movie->title }}</h2>
                        <p>{{ Str::limit($movie->description, 150) }}</p>
                        <div class="slide-meta">
                            <span><i class="fas fa-star" aria-hidden="true"></i>  {{ $movie->rating ?? '0.00' }}</span>
                            <span><i class="fas fa-clock" aria-hidden="true"></i> {{ $movie->duration }}</span>
                            <span><i class="fas fa-calendar-alt" aria-hidden="true"></i> {{ $movie->release_year }}</span>
                        </div>
                        <div class="slide-buttons">
                            <a href="{{ route('movie.detail', $movie->id) }}" class="btn btn-primary watch-btn" data-id="{{ $movie->id }}">
                                <i class="fas fa-play"></i> Watch Now
                            </a>

                            @auth
                                <form action="{{ route('mylist.add') }}" method="POST" class="add-watchlist-form inline-block">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <button type="submit" class="btn btn-secondary add-watchlist">
                                        <i class="fas fa-plus"></i> Add to Watchlist
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('user.login.form') }}" class="btn btn-secondary add-watchlist">
                                    <i class="fas fa-plus"></i> Add to Watchlist
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="slider-controls">
            @foreach($featuredMovies as $key => $movie)
                <div class="slider-dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $key }}" role="button" tabindex="0" aria-label="Go to slide {{ $key + 1 }}"></div>
            @endforeach
        </div>

        <div class="slider-arrows">
            <div class="arrow prev" role="button" tabindex="0" aria-label="Previous slide">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </div>
            <div class="arrow next" role="button" tabindex="0" aria-label="Next slide">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </div>
        </div>
    </section>

    <!-- Featured Movies Section -->
    <section class="featured-movies">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Movies</h2>
                <a href="{{ route('movies') }}" class="view-all">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="movies-grid">
                @foreach($featuredMovies as $movie)
                    <article class="movie-card">
                        <div class="card-image">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
                            <span class="card-badge">Featured</span>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $movie->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $movie->release_year }}</span>
                                <span class="card-rating">
                                    <i class="fas fa-star" aria-hidden="true"></i>  {{ $movie->rating ?? '0.00' }}
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
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trending Now -->
    <section class="movie-row trending">
        <div class="container">
            <h2 class="section-title">Trending Now</h2>
            <div class="movies-grid">
                @foreach($trendingMovies as $movie)
                    <article class="movie-card">
                        <div class="card-image">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
                            <span class="card-badge">Trending</span>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $movie->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $movie->release_year }}</span>
                                <span class="card-rating"><i class="fas fa-star" aria-hidden="true"></i>  {{ $movie->rating ?? '0.00' }}</span>
                            </div>
                            <p class="card-description">{{ Str::limit($movie->description, 100) }}</p>
                            <div class="card-actions">
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-play"></i> Watch</a>
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-info-circle"></i> Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Latest Releases -->
    <section class="movie-row latest">
        <div class="container">
            <h2 class="section-title">Latest Releases</h2>
            <div class="movies-grid">
                @foreach($latestMovies as $movie)
                    <article class="movie-card">
                        <div class="card-image">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
                            <span class="card-badge">Latest</span>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $movie->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $movie->release_year }}</span>
                                <span class="card-rating"><i class="fas fa-star" aria-hidden="true"></i>  {{ $movie->rating ?? '0.00' }}</span>
                            </div>
                            <p class="card-description">{{ Str::limit($movie->description, 100) }}</p>
                            <div class="card-actions">
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-play"></i> Watch</a>
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-info-circle"></i> Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Top Rated -->
    <section class="movie-row top-rated">
        <div class="container">
            <h2 class="section-title">Top Rated</h2>
            <div class="movies-grid">
                @foreach($topRatedMovies as $movie)
                    <article class="movie-card">
                        <div class="card-image">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
                            <span class="card-badge">Top Rated</span>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $movie->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $movie->release_year }}</span>
                                <span class="card-rating"><i class="fas fa-star" aria-hidden="true"></i>  {{ $movie->rating ?? '0.00' }}</span>
                            </div>
                            <p class="card-description">{{ Str::limit($movie->description, 100) }}</p>
                            <div class="card-actions">
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-play"></i> Watch</a>
                                <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-info-circle"></i> Details</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Browse by Category</h2>
            </div>

            <div class="categories-grid">
                @foreach($categories as $category)
                    <a href="{{ route('movies', ['category' => $category->name]) }}" class="category-card" role="button">
                        <i class="fas fa-film" aria-hidden="true"></i>
                        <h3>{{ $category->name }}</h3>
                        <span class="count">{{ $category->movies_count }} movies</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trailer Modal -->
    <div id="trailerModal" class="trailer-modal" role="dialog" aria-modal="true" aria-labelledby="modalTrailerTitle" tabindex="-1">
        <div class="modal-content">
            <video id="modalTrailer" controls aria-label="Movie trailer video"></video>
            <span class="close-modal" role="button" tabindex="0" aria-label="Close trailer">&times;</span>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
