document.addEventListener('DOMContentLoaded', function () {
  const slides = document.querySelectorAll('.video-slide');
  const dots = document.querySelectorAll('.slider-dot');

  /* -------------------- Hover-to-Play Trailer -------------------- */
  slides.forEach(slide => {
    const thumbnail = slide.querySelector('.video-thumbnail');
    const iframeContainer = slide.querySelector('.video-iframe-container');
    const iframe = slide.querySelector('.video-iframe');

    if (!iframe || !thumbnail || !iframeContainer) return;

    slide.addEventListener('mouseenter', () => {
      // Start playing trailer on hover
      iframeContainer.style.display = 'block';
      thumbnail.style.display = 'none';
      // Set src with autoplay=1&mute=1 if not already set (optional optimization)
      if (!iframe.src.includes('autoplay=1')) {
        iframe.src = iframe.src.includes('?') ? iframe.src + '&autoplay=1&mute=1' : iframe.src + '?autoplay=1&mute=1';
      }
    });

    slide.addEventListener('mouseleave', () => {
      // Stop playing trailer on mouse leave
      iframeContainer.style.display = 'none';
      thumbnail.style.display = 'block';
      iframe.src = iframe.src.split('?')[0]; // Reset src without autoplay to stop video
    });
  });

  /* -------------------- Slider Dot Navigation -------------------- */
  function setActiveSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
      dots[i].classList.toggle('active', i === index);
    });
  }

  let slideIndex = 0;
  let slideInterval;

  function startSlideShow() {
    slideInterval = setInterval(() => {
      slideIndex = (slideIndex + 1) % slides.length;
      setActiveSlide(slideIndex);
    }, 3000);
  }

  function stopSlideShow() {
    clearInterval(slideInterval);
  }

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      stopSlideShow();
      setActiveSlide(index);
      slideIndex = index;
      startSlideShow();
    });

    dot.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        dot.click();
      }
    });
  });

  setActiveSlide(0);
  startSlideShow();

  /* -------------------- Trailer Modal -------------------- */
  // Optional: Only if you add buttons/links with data-trailer-url to open modal
  const trailerModal = document.getElementById('trailerModal');
  const modalTrailer = document.getElementById('modalTrailer');
  const closeModalBtn = trailerModal ? trailerModal.querySelector('.close-modal') : null;

  if (trailerModal && modalTrailer && closeModalBtn) {
    document.querySelectorAll('[data-trailer-url]').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const trailerUrl = btn.getAttribute('data-trailer-url');
        if (!trailerUrl) {
          alert('Trailer not available.');
          return;
        }
        modalTrailer.src = trailerUrl;
        trailerModal.style.display = 'block';
        modalTrailer.focus();
        modalTrailer.play?.();
      });
    });

    function closeTrailerModal() {
      trailerModal.style.display = 'none';
      modalTrailer.pause?.();
      modalTrailer.src = '';
    }

    closeModalBtn.addEventListener('click', closeTrailerModal);
    closeModalBtn.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        closeTrailerModal();
      }
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && trailerModal.style.display === 'block') {
        closeTrailerModal();
      }
    });

    trailerModal.addEventListener('click', e => {
      if (e.target === trailerModal) {
        closeTrailerModal();
      }
    });
  }

  /* -------------------- AJAX Add to Watchlist -------------------- */
  document.querySelectorAll('.ajax-watchlist-form').forEach(form => {
    form.addEventListener('submit', async e => {
      e.preventDefault();

      const button = form.querySelector('button[type="submit"]');
      button.disabled = true;
      button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Adding...`;

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({}),
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();

        if (data.success) {
          button.innerHTML = `<i class="fas fa-check"></i> Added`;
          button.classList.add('added');
          setTimeout(() => {
            button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
            button.disabled = false;
            button.classList.remove('added');
          }, 3000);
        } else {
          alert(data.message || 'Failed to add to watchlist.');
          button.disabled = false;
          button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
        }
      } catch (error) {
        alert('An error occurred. Please try again.');
        console.error('Watchlist AJAX error:', error);
        button.disabled = false;
        button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
      }
    });
  });
});
