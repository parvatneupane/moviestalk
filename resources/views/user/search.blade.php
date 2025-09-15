@extends('components.sidebar')

@section('title', 'Search Movies')

@section('content')
<div class="container">
    <h2>Search Results</h2>
    
    <!-- Search Form -->
    <form method="GET" action="{{ route('movies.search') }}">
        <input type="text" name="query" placeholder="Search for movies..." value="{{ request()->query('query') }}">
        <button type="submit">Search</button>
    </form>

    @if($movies->isEmpty())
        <p>No movies found.</p>
    @else
        <ul>
            @foreach($movies as $movie)
                <li>
                    <h3>{{ $movie->title }}</h3>
                    <p>{{ $movie->description }}</p>
                    <a href="{{ route('movies.show', $movie) }}">View Details</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
