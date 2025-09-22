@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endpush

@section('title', 'Search Movies')

@section('content')
<div class="container">
    <h2>Search Results</h2>

    <div class="movies-grid">
        @if($movies->isEmpty())
            <p>No movies found.</p>
        @else
            @foreach($movies as $movie)
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
                                <i class="fas fa-star" aria-hidden="true"></i> {{ $movie->rating ?? '0.00' }}
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
        @endif
    </div>
</div>
@endsection
