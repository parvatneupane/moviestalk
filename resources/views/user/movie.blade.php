@extends('layouts.app')

@section('title', 'Movies - MovieTalks')


 @push('styles')
    <link rel="stylesheet" href="{{ asset('css/movie.css')}}">
@endpush

@section('content')
   
    <main>
        <div class="container">
            <!-- Filters Section -->
            <section class="filters-section">
                <div class="section-header">
                    <h2 class="section-title">All Movies</h2>
                    <div class="sort-group">
                        <label for="sort">Sort By:</label>
                        <select class="filter-select" id="sort">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recently Added</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                </div>

                <div class="filters">
                    <form id="filter-form" action="{{ route('movies') }}" method="GET">
                        <div class="filter-row">
                            <!-- Genre Filter -->
                            <div class="filter-group">
                                <label for="genre">Genre</label>
                                <select class="filter-select" id="genre" name="category">
                                    <option value="">All Genres</option>
                                    @foreach($movies as $category)
                                        <option value="{{ $category->category->slug }}" {{ request('category') == $category->category->slug ? 'selected' : '' }}>
                                            {{ $category->category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Year Filter -->
                            <div class="filter-group">
                                <label for="year">Release Year</label>
                                <select class="filter-select" id="year" name="year">
                                    <option value="">All Years</option>
                                    @foreach($years as $yearItem)
                                        <option value="{{ $yearItem->release_year }}" {{ request('year') == $yearItem->release_year ? 'selected' : '' }}>
                                            {{ $yearItem->release_year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Rating Filter -->
                            <div class="filter-group">
                                <label for="rating">Rating</label>
                                <select class="filter-select" id="rating" name="rating">
                                    <option value="">Any Rating</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1+ Stars</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Filter Action Buttons -->
                        <div class="filter-buttons">
                            <button type="button" class="btn-secondary" id="reset-filters">Reset Filters</button>
                            <button type="submit" class="btn-primary">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Movies Grid -->
            <section class="movies-grid-section">
                <div class="movies-grid">
                    @forelse($movies as $movie)
                        <!-- Movie Card -->
                        <div class="movie-card">
                            <div class="card-image">
                                <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }} poster">
                                @if($movie->created_at->diffInDays(now()) < 30)
                                    <span class="card-badge">New</span>
                                @elseif($movie->rating >= 4.5)
                                    <span class="card-badge">Top 10</span>
                                @endif
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">{{ $movie->title }}</h3>
                                <div class="card-meta">
                                    <span>{{ $movie->release_year }} • {{ $movie->category->name }}</span>
                                    <span class="card-rating">
                                        <i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}
                                    </span>
                                </div>
                                <p class="card-description">{{ Str::limit($movie->description, 100) }}</p>
                                <div class="card-actions">
                                    <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-play"></i> Watch</a>
                                    <a href="{{ route('movie.detail', $movie->id) }}"><i class="fas fa-info-circle"></i> Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="no-movies">
                            <i class="fas fa-film"></i>
                            <h3>No movies found</h3>
                            <p>Try adjusting your filters to see more results.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination Controls -->
                @if($movies->hasPages())
                    <div class="pagination">
                        {{ $movies->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/movie.js') }}"></script>
@endpush

    