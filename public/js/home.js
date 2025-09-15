document.addEventListener('DOMContentLoaded', function () {
    // -------------------
    // Slider Functionality
    // -------------------
    const slides = document.querySelectorAll('.video-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevArrow = document.querySelector('.arrow.prev');
    const nextArrow = document.querySelector('.arrow.next');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
        dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        currentSlide = index;
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => showSlide(parseInt(dot.dataset.slide)));
        dot.addEventListener('keypress', e => { if (e.key === 'Enter') showSlide(parseInt(dot.dataset.slide)); });
    });

    prevArrow.addEventListener('click', () => showSlide((currentSlide - 1 + slides.length) % slides.length));
    nextArrow.addEventListener('click', () => showSlide((currentSlide + 1) % slides.length));

    prevArrow.addEventListener('keypress', e => { if (e.key === 'Enter') showSlide((currentSlide - 1 + slides.length) % slides.length); });
    nextArrow.addEventListener('keypress', e => { if (e.key === 'Enter') showSlide((currentSlide + 1) % slides.length); });

  
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
