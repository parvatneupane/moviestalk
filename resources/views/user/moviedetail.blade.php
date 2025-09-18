@extends('layouts.app')

 @section('MovieTalks')

<link rel="stylesheet" href="{{ asset('css/moviedetail.css') }}">

@section('content')
<section class="movie-hero">
    <div class="container">
        <div class="movie-hero-content">
       
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
                
                 <img src="{{ asset($movie->poster) }}" alt="{{ $movie->title }} poster">
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
        <form action="{{ route('movie.remove-watchlist', $movie->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-watchlist remove">
                <i class="fas fa-times"></i> Remove from Watchlist
            </button>
        </form>
    @else
        <form action="{{ route('movie.toggle-watchlist', $movie->id) }}" method="POST" style="display:inline-block;">
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
<div class="rating-container">

    <!-- User Rating Form -->
    <div class="user-rating">
        <h3>Your Rating</h3>
<form id="rating-form" action="{{ url('movierating/' . $movie->id) }}" method="POST">
    @csrf
    <div class="star-rating">
        @for ($i = 1; $i <= 5; $i++)
            <label>
                <input type="radio" name="rating" value="{{ $i }}"
                    @if(!empty($userrating) && $userrating == $i) checked @endif>
                <i class="fas fa-star"></i>
            </label>
        @endfor
    </div>
    <button type="submit" class="rating-submit">Submit Rating</button>
</form>

<style>
.star-rating {
    display: flex;
    flex-direction: row;
}
.star-rating input { display: none; }
.star-rating i {
    font-size: 24px;
    color: #ccc;
    cursor: pointer;
    margin-right: 5px;
    transition: color 0.2s;
}
.star-rating i.filled { color: #f5b301; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-rating label i');
    const radios = document.querySelectorAll('.star-rating input');

    function fillStars(rating) {
        stars.forEach((star, index) => {
            star.classList.toggle('filled', index < rating);
        });
    }

    // Fill stars for previously selected rating
    const checkedRadio = document.querySelector('.star-rating input:checked');
    if (checkedRadio) fillStars(parseInt(checkedRadio.value));

    stars.forEach((star, index) => {
        const radio = radios[index];

        star.parentElement.addEventListener('mouseenter', () => fillStars(index + 1));
        star.parentElement.addEventListener('mouseleave', () => {
            const checked = document.querySelector('.star-rating input:checked');
            fillStars(checked ? parseInt(checked.value) : 0);
        });

        star.parentElement.addEventListener('click', () => {
            radio.checked = true;
            fillStars(index + 1);
        });
    });

    // AJAX form submission
    const form = document.getElementById('rating-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const rating = document.querySelector('input[name="rating"]:checked')?.value;
        if (!rating) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select a rating first!',
                background: '#2d2d2d',
                color: '#ffffff',
                iconColor: '#f44336'
            });
            return;
        }

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update user stars instantly
                fillStars(parseInt(data.rating));

                // Update average rating visually
                const avgRatingContainer = document.getElementById('average-rating');
                const avg = parseFloat(data.averageRating);

                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(avg)) {
                        starsHtml += '<i class="fas fa-star"></i>';
                    } else if (i - 0.5 <= avg) {
                        starsHtml += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        starsHtml += '<i class="far fa-star"></i>';
                    }
                }
                starsHtml += ` <span class="average-rating">${avg.toFixed(1)}/5</span>`;
                avgRatingContainer.innerHTML = starsHtml;

                // SweetAlert dark theme popup
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Rating submitted successfully',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#2d2d2d',
                    color: '#ffffff',
                    iconColor: '#f5b301'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!',
                    background: '#2d2d2d',
                    color: '#ffffff',
                    iconColor: '#f44336'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong!',
                background: '#2d2d2d',
                color: '#ffffff',
                iconColor: '#f44336'
            });
        });
    });
});
</script>






    </div>

    <!-- Display Average Rating -->
<div class="rating-stars mt-3" id="average-rating">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($rating))
            <i class="fas fa-star"></i>
        @elseif ($i - 0.5 <= $rating)
            <i class="fas fa-star-half-alt"></i>
        @else
            <i class="far fa-star"></i>
        @endif
    @endfor
    <span class="average-rating">{{ number_format($rating, 1) }}/5</span>
</div>


</div>
@endauth



</section>

<main class="container">

<section class="reviews-section">
    <h2 class="section-title">User Reviews</h2>

    @auth
 
<div class="review-form">
    <textarea 
        id="review-text"
        placeholder="Write your review..." 
        rows="4"
        class="form-control"
    >{{ old('review') }}</textarea>

    <button type="button" id="submit-review" class="btn btn-primary mt-2">Submit Review</button>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('submit-review').addEventListener('click', function() {
    let reviewText = document.getElementById('review-text').value.trim();

    if(!reviewText){
        Swal.fire({
            title: 'Error',
            text: 'Please write a review first!',
            icon: 'error',
            background: '#1e1e1e',
            color: '#fff',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Check if review already exists
    fetch("{{ route('movie.check-review', $movie->id) }}")
    .then(res => res.json())
    .then(data => {
        if(data.exists){
            // Confirmation alert (dark theme)
            Swal.fire({
                title: "Update Review?",
                text: "You already submitted a review. Do you want to update it?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, update it",
                cancelButtonText: "No, keep old",
                background: '#1e1e1e',
                color: '#fff',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then(result => {
                if(result.isConfirmed){
                    submitReview(reviewText, true);
                }
            });
        } else {
            submitReview(reviewText, false);
        }
    });
});

// AJAX function to submit or update review
function submitReview(review, update){
    fetch("{{ route('movie.submit-review', $movie->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            review: review,
            update: update
        })
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            title: data.success ? 'Success' : 'Error',
            text: data.message,
            icon: data.success ? 'success' : 'error',
            background: '#1e1e1e',
            color: '#fff',
            confirmButtonColor: data.success ? '#3085d6' : '#d33'
        }).then(() => {
            if(data.success) location.reload(); // refresh to show updated review
        });
    });
}
</script>



</div>



    @else
    <p>Please <a href="{{ route('user.login.form') }}">login</a> to write a review.</p>
    @endauth

 
   <div class="reviews-list mt-4">
    @forelse($reviews as $review)
        <div class="review-card border p-3 mb-3 rounded d-flex gap-3 align-items-start">
        
            <img 
  src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('images/default-avatar.png') }}" 
  alt="{{ $review->user->name }}'s Avatar" 
  class="rounded-circle"
  style="width: 50px; height: 50px; object-fit: cover;">

         
            <div class="flex-grow-1">
                <div class="review-header mb-1 d-flex justify-content-between align-items-center">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="text-muted" style="font-size: 0.85rem;">{{ $review->created_at->format('F j, Y') }}</span>
                </div>
                <div class="review-content">
                    <p class="mb-0">{{ $review->review }}</p>
                </div>
            </div>
        </div>
    @empty
        <p>No reviews yet.</p>
    @endforelse
</div>

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
