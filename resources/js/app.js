document.addEventListener('DOMContentLoaded', function () {
   
 
    // Watchlist Functionality
   
    const watchlistForms = document.querySelectorAll('form.add-watchlist-form');

    watchlistForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const movieId = form.querySelector('input[name="movie_id"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ movie_id: movieId })
            })
            .then(res => {
                if (res.status === 401) {
                  
                    window.location.href = '/user/login';
                } else if (res.ok) {
                    return res.json();
                } else {
                    throw new Error('Something went wrong.');
                }
            })
            .then(data => {
                if (data && data.success) {
                    // Optional: change button text to "Added"
                    const btn = form.querySelector('button.add-watchlist');
                    btn.innerHTML = '<i class="fas fa-check"></i> Added';
                    btn.disabled = true;
                }
            })
            .catch(err => console.error(err));
        });
    });

    // -------------------
    // Trailer Modal
    // -------------------
    const modal = document.getElementById('trailerModal');
    const modalVideo = document.getElementById('modalTrailer');
    const closeModal = modal.querySelector('.close-modal');

    document.querySelectorAll('.watch-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const trailerUrl = btn.dataset.trailer;
            if (trailerUrl) {
                modalVideo.src = trailerUrl;
                modal.classList.add('open');
            } else {
                alert('Trailer not available.');
            }
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.remove('open');
        modalVideo.pause();
        modalVideo.src = '';
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.remove('open');
            modalVideo.pause();
            modalVideo.src = '';
        }
    });
});




document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.video-slide');

    slides.forEach(slide => {
        const videoId = slide.getAttribute('data-video-id');
        const thumbnail = slide.querySelector('.video-thumbnail');
        const iframeContainer = slide.querySelector('.video-iframe-container');
        const iframe = slide.querySelector('.video-iframe');

        if (!videoId || !iframe || !thumbnail) return;

        slide.addEventListener('mouseenter', () => {
            // Set iframe src only on hover
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1`;
            thumbnail.style.display = 'none';
            iframeContainer.style.display = 'block';
        });

        slide.addEventListener('mouseleave', () => {
            // Remove iframe to stop video and restore image
            iframe.src = '';
            thumbnail.style.display = 'block';
            iframeContainer.style.display = 'none';
        });
    });
});
