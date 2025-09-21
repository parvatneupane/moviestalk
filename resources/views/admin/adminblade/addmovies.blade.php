@extends('layouts.admin')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/addmovies.css') }}">
</head>

@section('content')
<body>
<!-- Page Title -->
<div class="dashboard-title">
    <h1>Add New Movie</h1>
    <p>Add a new movie to the MovieTalk database</p>
</div>

<!-- Success / Error Messages -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Add Movie Form -->
<div class="form-container">
    <h2 class="form-title">Movie Information</h2>

    <form id="add-movie-form" enctype="multipart/form-data" action="{{ url('admin/addmovies') }}" method="POST">
        @csrf

        <!-- Movie Title -->
        <div class="form-group">
            <label for="movieTitle" class="form-label">Movie Title</label>
            <input type="text" name="title" id="movieTitle" class="form-input" 
                   value="{{ old('title') }}" placeholder="Enter movie title" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="movieDescription" class="form-label">Description</label>
            <textarea name="description" id="movieDescription" class="form-textarea" 
                      placeholder="Enter movie description" required>{{ old('description') }}</textarea>
        </div>

        <!-- Release Date & Runtime -->
        <div class="form-row">
            <div class="form-group">
                <label for="releaseDate" class="form-label">Release Date</label>
                <input type="date" name="release_date" id="releaseDate" class="form-input" 
                       value="{{ old('release_date') }}" required>
            </div>

            <div class="form-group">
                <label for="runtime" class="form-label">Runtime (minutes)</label>
                <input type="number" name="runtime" id="runtime" class="form-input" 
                       value="{{ old('runtime') }}" placeholder="Enter runtime" min="1" required>
            </div>
        </div>

        <!-- Director & Content Rating -->
        <div class="form-row">
            <div class="form-group">
                <label for="director" class="form-label">Director</label>
                <input type="text" name="director" id="director" class="form-input" 
                       value="{{ old('director') }}" placeholder="Enter director's name" required>
            </div>

            <div class="form-group">
                <label for="rating" class="form-label">Content Rating</label>
                <select name="content_rating" id="rating" class="form-select" required>
                    <option value="">Select rating</option>
                    @foreach(['G','PG','PG-13','R','NC-17'] as $rating)
                        <option value="{{ $rating }}" {{ old('content_rating') == $rating ? 'selected' : '' }}>{{ $rating }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Writer & Production -->
        <div class="form-row">
            <div class="form-group">
                <label for="writer" class="form-label">Writer</label>
                <input type="text" name="writer" id="writer" class="form-input" 
                       value="{{ old('writer') }}" placeholder="Enter writer's name" required>
            </div>

            <div class="form-group">
                <label for="production" class="form-label">Production</label>
                <input type="text" name="production" id="production" class="form-input" 
                       value="{{ old('production') }}" placeholder="Enter production's name" required>
            </div>
        </div>

        <!-- Categories (Checkboxes) -->
        <div class="form-group">
            <label class="form-label">Categories</label>
            <div class="form-check-group">
                @foreach($generes as $item)
                    <div class="form-check">
                        <input type="checkbox"
                               name="category[]"
                               value="{{ $item->id }}"
                               id="cat_{{ $item->id }}"
                               class="form-check-input"
                               {{ (is_array(old('category')) && in_array($item->id, old('category'))) ? 'checked' : '' }}>
                        <label for="cat_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cast & Release Year -->
        <div class="form-row">
            <div class="form-group">
                <label for="cast" class="form-label">Cast (comma separated)</label>
                <input type="text" name="cast" id="cast" class="form-input" 
                       value="{{ old('cast') }}" placeholder="e.g., Actor One, Actor Two" required>
            </div>

            <div class="form-group">
                <label for="release_year" class="form-label">Release Year (XXXX)</label>
                <input type="number" name="release_year" id="release_year" class="form-input" 
                       value="{{ old('release_year') }}" placeholder="Enter release year (e.g., 2024)" required>
            </div>
        </div>

        <!-- Poster Upload -->
        <div class="form-group">
            <label class="form-label">Movie Poster</label>
            <div class="file-upload" id="posterUpload">
                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                <div class="file-upload-text">Drag & drop your image here or <span>browse</span></div>
                <input type="file" name="poster" class="file-upload-input" id="posterInput" accept="image/*">
            </div>

            <!-- Image Preview -->
            <div id="posterPreviewContainer" style="margin-top: 10px;">
                <img id="posterPreview" src="#" alt="Poster Preview" style="display: none; max-width: 200px; border: 1px solid #ccc; padding: 5px;">
            </div>
        </div>

        <!-- Trailer URL -->
        <div class="form-group">
            <label for="trailer" class="form-label">Trailer URL (YouTube)</label>
            <input type="url" name="trailer_url" id="trailer" class="form-input" 
                   value="{{ old('trailer_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
        </div>

        <!-- Featured / Trending -->
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}> Featured
            </label>
            <label style="margin-left:20px;">
                <input type="checkbox" name="is_trending" {{ old('is_trending') ? 'checked' : '' }}> Trending
            </label>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="reset" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Movie</button>
        </div>
    </form>
</div>

<!-- Poster Preview JS -->
<script>
document.getElementById('posterInput').addEventListener('change', function(event) {
    const input = event.target;
    const preview = document.getElementById('posterPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
});
</script>

@endsection
