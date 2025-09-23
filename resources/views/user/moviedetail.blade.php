@extends('layouts.app')

 @section('MovieTalks')

<link rel="stylesheet" href="{{ asset('css/moviedetail.css') }}">

@section('content')
<section class="movie-hero">
    <div class="container">
        <div class="movie-hero-content">
       
            <div class="movie-trailer">
                @if($movie->youtube_id)
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $movie->youtube_id }}" 
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
               
                    <div class="movie-actions">
                        @auth 
                            <button id="watchlist-btn" data-movie="{{ $movie->id }}" class="{{ auth()->user()->watchlist->contains('movie_id', $movie->id) ? 'in-watchlist' : '' }}">
                                {{ auth()->user()->watchlist->contains('movie_id', $movie->id) ? 'Added to Watchlist' : ' + Add to Watchlist' }}
                            </button>
                        @endauth          
                    </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.getElementById('watchlist-btn');
                    btn.addEventListener('click', async function() {
                        const movieId = this.dataset.movie;
                        const token = '{{ csrf_token() }}';

                        const res = await fetch(`/watchlist/toggle/${movieId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();
                        if(data.status === 'added') {
                            btn.textContent = 'Added to Watchlist';
                            btn.classList.add('in-watchlist'); // change color
                        } else if(data.status === 'removed') {
                            btn.textContent = ' + Add to Watchlist';
                            btn.classList.remove('in-watchlist'); // revert color
                        }
                    });
                });
                </script>

                <div class="movie-meta">
                    <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                    <span><i class="fas fa-clock"></i> {{ $movie->runtime ?? 'N/A' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_date ?? 'N/A' }}</span>
                   
                   <span><i class="fas fa-film"></i> 
                        @if($movie->categories->count())
                            {{ $movie->categories->pluck('name')->join(', ') }}
                        @else
                            N/A
                        @endif
                    </span>



                </div>


                <p class="movie-description">{{ $movie->description }}</p>
            </div>
        </div>

       
        <div class="movieinfo">
            <div class="movie-details-grid">
                <div class="detail-item"><span class="detail-label">Director:</span> <span class="detail-value">{{ $movie->director ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Writers:</span> <span class="detail-value">{{ $movie->writer ?? 'N/A' }}</span></div>
                <div class="detail-item"><span class="detail-label">Stars:</span> <span class="detail-value">{{ $movie->cast ?? 'N/A' }}</span></div>
                
                <div class="detail-item">
                    <span class="detail-label">Genres:</span> 
                    <span class="detail-value">
                        @if($movie->categories->count())
                            {{ $movie->categories->pluck('name')->join(', ') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>



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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star-rating label i');
    const radios = document.querySelectorAll('.star-rating input');

    // -------------------------
    // Function to fill stars visually
    // -------------------------
    function fillStars(rating) {
        stars.forEach((star, index) => {
            star.classList.toggle('filled', index < rating);
        });
    }

    // Fill stars for previously selected rating
    const checkedRadio = document.querySelector('.star-rating input:checked');
    if (checkedRadio) fillStars(parseInt(checkedRadio.value));

    // -------------------------
    // Hover & click events
    // -------------------------
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

    // -------------------------
    // AJAX form submission
    // -------------------------
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
                // 1️⃣ Update user stars
                fillStars(parseInt(data.rating));

                // 2️⃣ Update average rating stars & value
                const avg = parseFloat(data.averageRating);
                const avgRatingContainer = document.getElementById('average-rating');

                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(avg)) starsHtml += '<i class="fas fa-star"></i>';
                    else if (i - 0.5 <= avg) starsHtml += '<i class="fas fa-star-half-alt"></i>';
                    else starsHtml += '<i class="far fa-star"></i>';
                }
                starsHtml += ` <span class="average-rating">${avg.toFixed(1)}/5</span>`;
                avgRatingContainer.innerHTML = starsHtml;

                // 3️⃣ Refresh total rating count dynamically
                if (window.refreshRatingChart) window.refreshRatingChart();

                // 4️⃣ Success popup
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Rating submitted successfully',
                    timer: 1800,
                    showConfirmButton: false,
                    background: '#2d2d2d',
                    color: '#ffffff',
                    iconColor: '#f5b301'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Something went wrong!',
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
                text: 'Something went wrong! Please try again.',
                background: '#2d2d2d',
                color: '#ffffff',
                iconColor: '#f44336'
            });
        });
    });
});

</script>




    </div>

<div class="rating-dashboard d-flex gap-4 align-items-start mt-3" id="rating-dashboard">
    <!-- Average Rating Stars -->
    <div class="average-rating-stars text-center">
        <div id="average-rating">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= floor($rating))
                    <i class="fas fa-star"></i>
                @elseif ($i - 0.5 <= $rating)
                    <i class="fas fa-star-half-alt"></i>
                @else
                    <i class="far fa-star"></i>
                @endif
            @endfor
            <div>{{ number_format($rating, 1) }}/5</div>
        </div>
        <div id="review-count" style="color: #ffffff;">0 Ratings</div>
    </div>

    <!-- Review Distribution Chart -->
    <div class="rating-chart" style="flex:1;">
        <canvas id="reviewChart" height="150"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let reviewChart;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('reviewChart').getContext('2d');

    // Initialize empty chart
    reviewChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Ratings',
                data: [0,0,0,0,0],
                backgroundColor: ['#f44336','#ff9800','#ffeb3b','#8bc34a','#4caf50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Ratings Distribution' }
            },
            scales: { 
                y: { beginAtZero: true, ticks: { stepSize: 1, precision:0 } } 
            }
        }
    });

    // Fetch latest rating counts and update chart
function refreshRatingChart() {
    fetch("{{ route('movies.ratingCounts', $movie->id) }}")
        .then(res => res.json())
        .then(data => {
            // 1️⃣ Update chart bars
            reviewChart.data.datasets[0].data = [
                data[1] ?? 0,
                data[2] ?? 0,
                data[3] ?? 0,
                data[4] ?? 0,
                data[5] ?? 0
            ];
            reviewChart.update();

            // 2️⃣ Calculate total ratings
            const totalRatings = Object.values(data).reduce((a, b) => a + b, 0);

            // 3️⃣ Update total rating count in DOM
            const countEl = document.getElementById('review-count');
            if (countEl) {
                countEl.textContent = totalRatings + ' Ratings';
                countEl.style.color = '#ffffff';
            }

            // 4️⃣ Update average rating stars dynamically
            const sum = Object.entries(data).reduce((acc, [star, count]) => acc + star * count, 0);
            const avg = totalRatings > 0 ? sum / totalRatings : 0;

            const avgRatingContainer = document.getElementById('average-rating');
            if (avgRatingContainer) {
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(avg)) starsHtml += '<i class="fas fa-star"></i>';
                    else if (i - 0.5 <= avg) starsHtml += '<i class="fas fa-star-half-alt"></i>';
                    else starsHtml += '<i class="far fa-star"></i>';
                }
                starsHtml += ` <span class="average-rating">${avg.toFixed(1)}/5</span>`;
                avgRatingContainer.innerHTML = starsHtml;
            }
        })
        .catch(err => console.error(err));
}

    // Initial chart load
    refreshRatingChart();

    // Expose globally to refresh after rating submit
    window.refreshRatingChart = refreshRatingChart;
});
</script>





<style>
    .rating-dashboard {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.average-rating-stars {
    text-align: center;
}

.average-rating-stars i {
    color: #f5b301;
    font-size: 1.2rem;
    margin-right: 2px;
}

</style>

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

@php
    $initialReviews = $movie->reviews()->latest()->take(2)->get();
    $totalReviews = $movie->reviews()->count();
@endphp

<div class="movie-reviews" id="reviews-container" data-movie-id="{{ $movie->id }}" data-total="{{ $totalReviews }}">
    @include('user.showmorereview', ['reviews' => $initialReviews])
</div>

@if($totalReviews > 2)
    <button id="toggle-reviews-btn" class="btn btn-outline-light mt-2" data-offset="2">
        Show More
    </button>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewsContainer = document.getElementById('reviews-container');
    const toggleBtn = document.getElementById('toggle-reviews-btn');
    if (!toggleBtn || !reviewsContainer) return;

    const movieId = reviewsContainer.dataset.movieId;
    const totalReviews = parseInt(reviewsContainer.dataset.total);
    let offset = parseInt(toggleBtn.dataset.offset);
    let showingAll = false;

    toggleBtn.addEventListener('click', async function() {
        if (!showingAll) {
            // Show more: fetch next batch
            try {
                const res = await fetch(`/movies/${movieId}/more-reviews?offset=${offset}`);
                const data = await res.text();
                reviewsContainer.insertAdjacentHTML('beforeend', data);

                offset += 2;
                toggleBtn.dataset.offset = offset;

                if (reviewsContainer.children.length >= totalReviews) {
                    toggleBtn.textContent = 'Show Less';
                    showingAll = true;
                }
            } catch(err) {
                console.error(err);
                alert('Failed to load more reviews.');
            }
        } else {
            // Show less: remove extra reviews
            const initialCount = 2;
            const allCards = reviewsContainer.querySelectorAll('.review-card');
            allCards.forEach((card, index) => {
                if (index >= initialCount) card.remove();
            });

            toggleBtn.textContent = 'Show More';
            offset = initialCount;
            toggleBtn.dataset.offset = offset;
            showingAll = false;
        }
    });
});
</script>


</section>


    <!-- Similar Movies -->
<section class="similar-movies">
    <h2 class="section-title">You Might Also Like</h2>
    <div class="movies-grid">
        @foreach($similarMovies as $similar)
            <div class="movie-card">
                <a href="{{ route('movie.detail', $similar->id) }}">
                    <div class="movie-card-poster">
                        <img src="{{ asset('storage/' . $similar->poster) }}" alt="{{ $similar->title }}">
                    </div>
                    <div class="movie-card-info">
                        <h3 class="movie-card-title">{{ $similar->title }}</h3>
                        <div class="movie-card-meta">
                            <span class="movie-card-year">{{ \Carbon\Carbon::parse($similar->release_date)->format('Y') }}</span> |
                           <span class="movie-card-genre">
                                @foreach($similar->categories as $cat)
                                    {{ $cat->name }}@if(!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="movie-card-rating">
                            <i class="fas fa-star" style="color: #ffc107;"></i>
                            <span>{{ number_format($similar->rating ?? 0, 1) }}</span>
                        </div>
                    </div>
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
