@extends('layouts.admin')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Movie - MovieTalk Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admincss/addmovies.css') }}">
</head>

@section('content')

    <!-- Page Title -->
    <div class="dashboard-title">
        <h1>Update Movie</h1>
        <p>Update details for <strong>{{ $movie->title }}</strong></p>
    </div>

    <!-- Update Movie Form -->
    <div class="form-container">
        <h2 class="form-title">Movie Information</h2>

        <form id="update-movie-form" enctype="multipart/form-data" 
              action="{{ url('admin/updatemovies/'. $movie->id) }}" 
              method="POST">
            @csrf
           

            <div class="form-group">
                <label for="movieTitle" class="form-label">Movie Title</label>
                <input type="text" name="title" id="movieTitle" class="form-input" 
                       value="{{ old('title', $movie->title) }}" required>
            </div>

            <div class="form-group">
                <label for="movieDescription" class="form-label">Description</label>
                <textarea name="description" id="movieDescription" class="form-textarea" required>{{ old('description', $movie->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="releaseDate" class="form-label">Release Date</label>
                    <input type="date" name="release_date" id="releaseDate" class="form-input" 
                           value="{{ old('release_date', $movie->release_date) }}" required>
                </div>

                <div class="form-group">
                    <label for="runtime" class="form-label">Runtime (minutes)</label>
                    <input type="number" name="runtime" id="runtime" class="form-input" 
                           value="{{ old('runtime', $movie->runtime) }}" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="director" class="form-label">Director</label>
                    <input type="text" name="director" id="director" class="form-input" 
                           value="{{ old('director', $movie->director) }}" required>
                </div>

                <div class="form-group">
                    <label for="rating" class="form-label">Content Rating</label>
                    <select name="content_rating" id="rating" class="form-select" required>
                        <option value="">Select rating</option>
                        @foreach (['G','PG','PG-13','R','NC-17'] as $rating)
                            <option value="{{ $rating }}" {{ old('content_rating', $movie->content_rating) == $rating ? 'selected' : '' }}>
                                {{ $rating }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="writer" class="form-label">Writer</label>
                    <input type="text" name="writer" id="writer" class="form-input" 
                           value="{{ old('writer', $movie->writer) }}" required>
                </div>

                <div class="form-group">
                    <label for="production" class="form-label">Production</label>
                    <input type="text" name="production" id="production" class="form-input" 
                           value="{{ old('production', $movie->production) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach ($generes as $item)
                        <option value="{{ $item->id }}" 
                            {{ old('category', $movie->category_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cast" class="form-label">Cast (comma separated)</label>
                    <input type="text" name="cast" id="cast" class="form-input" 
                           value="{{ old('cast', $movie->cast) }}" required>
                </div>

                <div class="form-group">
                    <label for="release_year" class="form-label">Release Year (XXXX)</label>
                    <input type="number" name="release_year" id="release_year" class="form-input" 
                           value="{{ old('release_year', $movie->release_year) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Movie Poster</label>
                <div class="file-upload" id="posterUpload">
                    <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                    <div class="file-upload-text">Drag & drop your image here or <span>browse</span></div>
                    <input type="file" name="poster" class="file-upload-input" id="posterInput" accept="image/*">
                </div>

                <!-- Show current poster if exists -->
                @if ($movie->poster)
                    <div id="posterPreviewContainer" style="margin-top: 10px;">
                        <img src="{{ asset('storage/' . $movie->poster) }}" 
                             alt="Current Poster" 
                             style="max-width: 200px; border: 1px solid #ccc; padding: 5px;">
                    </div>
                @endif

                <!-- New Image Preview -->
                <div id="posterPreviewContainer" style="margin-top: 10px;">
                    <img id="posterPreview" src="#" alt="Poster Preview" 
                         style="display: none; max-width: 200px; border: 1px solid #ccc; padding: 5px;" />
                </div>
            </div>

            <div class="form-group">
                <label for="trailer" class="form-label">Trailer URL (YouTube)</label>
                <input type="url" name="trailer" id="trailer" class="form-input" 
                       value="{{ old('trailer', $movie->trailer) }}" required>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_featured" 
                           {{ old('is_featured', $movie->is_featured) ? 'checked' : '' }}> Featured
                </label>
                <label style="margin-left: 20px;">
                    <input type="checkbox" name="is_trending" 
                           {{ old('is_trending', $movie->is_trending) ? 'checked' : '' }}> Trending
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Movie</button>
            </div>
        </form>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        document.getElementById('posterInput').addEventListener('change', function (event) {
            const input = event.target;
            const preview = document.getElementById('posterPreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
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
