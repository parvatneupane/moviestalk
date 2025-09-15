document.addEventListener('DOMContentLoaded', function() {
    console.log('MovieDetail page initialization...');

    const movieId = getMovieIdFromURL();
    if (!movieId) {
        showError('No movie selected.');
        return;
    }

    showLoading(true);
    fetchMovieDetails(movieId)
        .then(movie => {
            showLoading(false);
            if (!movie) {
                showError('Movie not found.');
                return;
            }
            renderMovieDetails(movie);
            initWatchlistButton(movie.title);
            initBackButton();
        })
        .catch(err => {
            showLoading(false);
            showError('Failed to load movie details.');
            console.error(err);
        });
});

function getMovieIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');  // expects URL like moviedetail.html?id=123
}

function fetchMovieDetails(id) {
    
    return new Promise((resolve) => {
        setTimeout(() => {
            // Dummy data for demo
            const dummyData = {
                id: '123',
                title: 'Inception',
                year: 2010,
                genre: 'Sci-Fi, Thriller',
                rating: 8.8,
                synopsis: 'A thief who steals corporate secrets through dream-sharing technology is given the inverse task of planting an idea into the mind of a CEO.',
                director: 'Christopher Nolan',
                cast: ['Leonardo DiCaprio', 'Joseph Gordon-Levitt', 'Ellen Page'],
                poster: 'https://example.com/posters/inception.jpg',
                trailerUrl: 'https://www.youtube.com/embed/YoHD9XEInc0'
            };

            if (id === dummyData.id) resolve(dummyData);
            else resolve(null);
        }, 1000);
    });
}

function showLoading(show) {
    const loadingEl = document.getElementById('loading');
    if (loadingEl) loadingEl.style.display = show ? 'block' : 'none';
}

function showError(message) {
    const errorEl = document.getElementById('error-message');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
    }
}

function renderMovieDetails(movie) {
    const container = document.getElementById('movie-detail-container');
    if (!container) return;

    container.innerHTML = `
        <div class="movie-detail-poster">
            <img src="${movie.poster}" alt="Poster of ${movie.title}">
        </div>
        <div class="movie-detail-info">
            <h1>${movie.title} <span class="year">(${movie.year})</span></h1>
            <p><strong>Genre:</strong> ${movie.genre}</p>
            <p><strong>Rating:</strong> ${movie.rating}</p>
            <p><strong>Director:</strong> ${movie.director}</p>
            <p><strong>Cast:</strong> ${movie.cast.join(', ')}</p>
            <p class="synopsis">${movie.synopsis}</p>
            <button id="watchlist-btn" class="btn-primary"><i class="fas fa-plus"></i> Add to Watchlist</button>
            <button id="back-btn" class="btn-secondary">← Back to Movies</button>
            <div class="trailer">
                <h3>Trailer</h3>
                <iframe width="560" height="315" src="${movie.trailerUrl}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    `;
}

function initWatchlistButton(movieTitle) {
    const btn = document.getElementById('watchlist-btn');
    if (!btn) return;

    // Update button if already in watchlist
    let watchlist = JSON.parse(localStorage.getItem('movietalks_watchlist') || '[]');
    if (watchlist.includes(movieTitle)) {
        btn.innerHTML = '<i class="fas fa-check"></i> In Watchlist';
        btn.classList.add('in-watchlist');
    }

    btn.addEventListener('click', () => {
        watchlist = JSON.parse(localStorage.getItem('movietalks_watchlist') || '[]');
        if (watchlist.includes(movieTitle)) {
            watchlist = watchlist.filter(title => title !== movieTitle);
            btn.innerHTML = '<i class="fas fa-plus"></i> Add to Watchlist';
            btn.classList.remove('in-watchlist');
            showNotification(`Removed "${movieTitle}" from your watchlist`);
        } else {
            watchlist.push(movieTitle);
            btn.innerHTML = '<i class="fas fa-check"></i> In Watchlist';
            btn.classList.add('in-watchlist');
            showNotification(`Added "${movieTitle}" to your watchlist`);
        }
        localStorage.setItem('movietalks_watchlist', JSON.stringify(watchlist));
    });
}

function initBackButton() {
    const backBtn = document.getElementById('back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.history.back();
        });
    }
}

function showNotification(message, duration = 3000) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #333;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s, transform 0.3s;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, duration);
}
