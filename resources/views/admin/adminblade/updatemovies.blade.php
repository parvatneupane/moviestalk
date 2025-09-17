@extends('layouts.admin')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin - Edit Movie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/updatemovies.css') }}">
</head>

<body>
@section('content')

<div class="container">
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
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

        <!-- Page Title -->
        <div class="dashboard-title">
            <h1>Edit Movie</h1>
            <p>Update movie information in the MovieTalk database</p>
        </div>

        <!-- Update Movie Form -->
        <div class="form-container">
            <h2 class="form-title">Movie Information</h2>

            @if(session('success'))
                <div style="color: green;">{{ session('success') }}</div>
            @endif

            <form id="update-movie-form" enctype="multipart/form-data" 
                  action="{{ url('admin/updatemovies/' . $movie->id) }}" method="POST">
                @csrf
               

                <!-- Title -->
                <div class="form-group">
                    <label for="movieTitle" class="form-label">Movie Title</label>
                    <input type="text" name="title" id="movieTitle" 
                           class="form-input" 
                           value="{{ old('title', $movie->title) }}" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="movieDescription" class="form-label">Description</label>
                    <textarea name="description" id="movieDescription" 
                              class="form-textarea" required>{{ old('description', $movie->description) }}</textarea>
                </div>

                <!-- Release date & runtime -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="releaseDate" class="form-label">Release Date</label>
                        <input type="date" name="release_date" id="releaseDate" 
                               class="form-input" 
                              value="{{ old('release_date', $movie->release_date) }}"
 required>
                    </div>
                    <div class="form-group">
                        <label for="runtime" class="form-label">Runtime (minutes)</label>
                        <input type="number" name="runtime" id="runtime" 
                               class="form-input" min="1" 
                               value="{{ old('runtime', $movie->runtime) }}" required>
                    </div>
                </div>

                <!-- Director & Rating -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="director" class="form-label">Director</label>
                        <input type="text" name="director" id="director" 
                               class="form-input" 
                               value="{{ old('director', $movie->director) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rating" class="form-label">Content Rating</label>
                        <select name="content_rating" id="rating" class="form-select" required>
                            <option value="">Select rating</option>
                            @foreach(['G','PG','PG-13','R','NC-17'] as $rating)
                                <option value="{{ $rating }}" 
                                    {{ old('content_rating', $movie->content_rating) == $rating ? 'selected' : '' }}>
                                    {{ $rating }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Writer & Production -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="writer" class="form-label">Writer</label>
                        <input type="text" name="writer" id="writer" 
                               class="form-input" 
                               value="{{ old('writer', $movie->writer) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="production" class="form-label">Production</label>
                        <input type="text" name="production" id="production" 
                               class="form-input" 
                               value="{{ old('production', $movie->production) }}" required>
                    </div>
                </div>

                <!-- Genres -->
                <div class="form-group">
                    <label class="form-label">Genres</label>
                    <div class="checkbox-group">
                 @foreach($generes as $genre)
    <input type="checkbox" 
           name="genres[]" 
           value="{{ $genre->id }}"
           {{ in_array($genre->id, $selectedGenres) ? 'checked' : '' }}>
    {{ $genre->name }}
@endforeach

                    </div>
                </div>


                <div class="form-row">
                <!-- Cast -->
                    <div class="form-group">
                    <label for="cast" class="form-label">Cast (comma separated)</label>
                    <input type="text" name="cast" id="cast" 
                           class="form-input"  value="{{ old('cast', $movie->cast) }}" required>
                     </div>

                         <div class="form-group">
                        <label for="release_year" class="form-label">Release_Year (XXXX)</label>
                        <input type="number" name="release_year" id="release_year" 
                               class="form-input" min="1" 
                               value="{{ old('release_year', $movie->release_year) }}" required>
                        </div>

                </div>


                <!-- Poster -->
                <div class="form-group">
                    <label class="form-label">Movie Poster</label>
                    <div class="file-upload" id="posterUpload">
                        <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                        <div class="file-upload-text">
                            Drag & drop your image here or <span>browse</span>
                        </div>
                        <input type="file" name="poster" class="file-upload-input" 
                               id="posterInput" accept="image/*">
                        @if($movie->poster)
                            <p>Current Poster: 
                                <img src="{{ asset('storage/'.$movie->poster) }}" 
                                     alt="poster" width="100">
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Trailer -->
                <div class="form-row">
                <div class="form-group">
                   <input type="url" name="trailer_url" id="trailer_url" class="form-input"
                  value="{{ old('trailer_url', $movie->trailer_url) }}" required>

                </div>

                <div class="form-group">
                        <label for="category" class="form-label">Category</label>
<select name="category" id="category" class="form-select" required>
    <option value="">Select Category</option>
    @foreach ($generes as $item)
        <option value="{{ $item->id }}" {{ old('category', $movie->category) == $item->id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
    @endforeach
</select>

                    </div>
                </div>
                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Movie
                    </button>
                    <a href="{{ url('/admin/movies') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Movies List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection