document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1️⃣ Sorting Watchlist
    const sortSelect = document.getElementById('sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const sortValue = this.value;
            const movieGrid = document.querySelector('.movies-grid');
            movieGrid.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

            fetch(`/mylist/sort/${sortValue}`)
                .then(res => res.json())
                .then(data => {
                    movieGrid.innerHTML = '';
                    data.movies.forEach(item => {
                        movieGrid.innerHTML += createMovieCard(item);
                    });
                    attachCardEventListeners(); // Reattach listeners for new cards
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to sort the watchlist. Please try again.');
                });
        });
    }

    // 2️⃣ Attach events for all existing movie cards
    function attachCardEventListeners() {
        // Toggle watched/unwatched
        document.querySelectorAll('.watched-toggle').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.dataset.id;
                const watchedStatus = this.dataset.watched === 'true';

                this.disabled = true;

                fetch(`/mylist/toggle-watched/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const icon = this.querySelector('i');
                        const cardImage = this.closest('.card-image');

                        if (data.watched) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            this.dataset.watched = 'true';

                            if (!cardImage.querySelector('.card-badge')) {
                                const badge = document.createElement('span');
                                badge.classList.add('card-badge');
                                badge.innerText = 'Watched';
                                cardImage.appendChild(badge);
                            }
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            this.dataset.watched = 'false';

                            const badge = cardImage.querySelector('.card-badge');
                            if (badge) badge.remove();
                        }
                    } else {
                        alert('Failed to update watched status.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });

        // Remove movie from watchlist
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = this.dataset.id;
                this.disabled = true;

                fetch(`/mylist/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.movie-card').remove();
                    } else {
                        alert('Failed to remove movie.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });

        // Continue button increments progress
        document.querySelectorAll('.card-actions a.continue-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const card = this.closest('.movie-card');
                const progressBar = card.querySelector('.progress-bar .progress');
                const itemId = card.querySelector('.progress-bar').dataset.id;
                let currentWidth = parseInt(progressBar.style.width) || 0;
                let newProgress = Math.min(currentWidth + 10, 100);

                this.disabled = true;

                fetch(`/mylist/update-progress/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ progress: newProgress })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        progressBar.style.width = `${data.progress}%`;
                        card.querySelector('.progress-container span').innerText = `Progress: ${data.progress}%`;

                        if (data.watched && !card.querySelector('.card-badge')) {
                            const badge = document.createElement('span');
                            badge.classList.add('card-badge');
                            badge.innerText = 'Watched';
                            card.querySelector('.card-image').appendChild(badge);
                        }
                    } else {
                        alert('Failed to update progress.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong. Please try again.');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    }

    attachCardEventListeners(); // Initial attach

    // 3️⃣ Function to create movie card HTML dynamically
    function createMovieCard(item) {
        const poster = item.movie.poster ? `/storage/${item.movie.poster}` : '/images/default.jpg';
        const title = item.movie.title || 'Untitled';
        const category = item.movie.category?.name || 'Uncategorized';
        const description = item.movie.description ? item.movie.description.slice(0, 100) + '...' : 'No description available.';
        const rating = item.movie.rating ? item.movie.rating.toFixed(1) : 'N/A';

        return `
            <div class="movie-card" data-watched="${item.watched ? 'true' : 'false'}">
                <div class="card-image">
                    <img src="${poster}" alt="${title} Poster">
                    ${item.watched ? '<span class="card-badge">Watched</span>' : ''}
                    <div class="card-actions-overlay">
                        <button class="watched-toggle" data-id="${item.id}" data-watched="${item.watched ? 'true' : 'false'}">
                            <i class="${item.watched ? 'fas fa-check-circle' : 'far fa-check-circle'}"></i>
                        </button>
                        <div class="remove-btn" data-id="${item.id}">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">${title}</h3>
                    <div class="card-meta">
                        <span>${item.movie.release_year} • ${category}</span>
                        <span class="card-rating"><i class="fas fa-star"></i> ${rating}</span>
                    </div>
                    <p class="card-description">${description}</p>
                    <div class="progress-container">
                        <span>Progress: ${item.progress}%</span>
                        <div class="progress-bar" data-id="${item.id}" data-progress="${item.progress}">
                            <div class="progress" style="width: ${item.progress}%;"></div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="/user/movie/${item.movie.id}" class="continue-btn"><i class="fas fa-play"></i> Continue</a>
                        <a href="/user/movie/${item.movie.id}"><i class="fas fa-info-circle"></i> Details</a>
                    </div>
                </div>
            </div>
        `;
    }
});
