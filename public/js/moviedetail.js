// Function to handle adding/removing a movie to/from the watchlist
function toggleWatchlist(movieId) {
    // Send a POST request to add/remove the movie from the watchlist
    fetch(`/movie/${movieId}/toggle-watchlist`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for security
        },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.json())
    .then(data => {
        const watchlistBtn = document.getElementById('watchlist-btn');
        
        // Update the button text and style based on whether it's added or removed
        if (data.status === 'added') {
            watchlistBtn.innerHTML = '<i class="fas fa-check"></i> In Watchlist';
            watchlistBtn.classList.add('added');
        } else if (data.status === 'removed') {
            watchlistBtn.innerHTML = '<i class="fas fa-plus"></i> Add to Watchlist';
            watchlistBtn.classList.remove('added');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong while adding/removing from the watchlist.');
    });
}

// Function to handle submitting the rating
function submitRating(movieId) {
    const rating = document.querySelectorAll('.star-rating i.selected').length; // Count the selected stars

    if (rating === 0) {
        alert('Please select a rating before submitting.');
        return;
    }

    // Send the rating to the backend
    fetch(`/movie/${movieId}/submit-rating`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for security
        },
        body: JSON.stringify({ rating: rating })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Your rating has been submitted.");
            // Optionally, update the average rating on the page without refreshing
            document.querySelector('.rating-score').innerText = data.new_average_rating;
        } else {
            alert("Something went wrong. Please try again.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong while submitting the rating.');
    });
}

// Function to handle star click to select rating
document.querySelectorAll('.star-rating i').forEach(star => {
    star.addEventListener('click', function() {
        // Remove the 'selected' class from all stars
        document.querySelectorAll('.star-rating i').forEach(star => star.classList.remove('selected'));
        
        // Add the 'selected' class to the clicked star and all previous stars
        let ratingValue = parseInt(this.getAttribute('data-value'));
        for (let i = 0; i < ratingValue; i++) {
            document.querySelectorAll('.star-rating i')[i].classList.add('selected');
        }
    });
});

// Function to handle the review form submission
document.querySelector('.review-submit').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const reviewTitle = document.getElementById('review-title').value;
    const reviewContent = document.getElementById('review-content').value;

    if (!reviewTitle || !reviewContent) {
        alert('Please fill out both the title and content of your review.');
        return;
    }

    // Send the review to the backend
    const movieId = document.getElementById('movie-id').value; // Make sure you add the movie ID to a hidden input field

    fetch(`/movie/${movieId}/submit-review`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for security
        },
        body: JSON.stringify({ title: reviewTitle, content: reviewContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Your review has been submitted.");
            // Optionally, you can add the review to the page without refreshing
        } else {
            alert("Something went wrong. Please try again.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong while submitting your review.');
    });
});
