document.addEventListener('DOMContentLoaded', () => {
  /* -------------------- Video Slider -------------------- */
  const slides = document.querySelectorAll('.video-slide');
  const dots = document.querySelectorAll('.slider-dot');
  const prevArrow = document.querySelector('.arrow.prev');
  const nextArrow = document.querySelector('.arrow.next');
  let currentIndex = 0;
  let slideInterval = null;

  function setActiveSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });
    currentIndex = index;
  }

  function nextSlide() {
    let nextIndex = (currentIndex + 1) % slides.length;
    setActiveSlide(nextIndex);
  }

  function prevSlide() {
    let prevIndex = (currentIndex - 1 + slides.length) % slides.length;
    setActiveSlide(prevIndex);
  }

  // Auto slide every 8 seconds
  function startSlideShow() {
    slideInterval = setInterval(nextSlide, 8000);
  }

  function stopSlideShow() {
    clearInterval(slideInterval);
  }

  // Event listeners for arrows
  if (prevArrow && nextArrow) {
    prevArrow.addEventListener('click', () => {
      stopSlideShow();
      prevSlide();
      startSlideShow();
    });
    nextArrow.addEventListener('click', () => {
      stopSlideShow();
      nextSlide();
      startSlideShow();
    });

    prevArrow.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        prevArrow.click();
      }
    });
    nextArrow.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        nextArrow.click();
      }
    });
  }

  // Event listeners for dots
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      stopSlideShow();
      setActiveSlide(index);
      startSlideShow();
    });
    dot.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        dot.click();
      }
    });
  });

  setActiveSlide(0);
  startSlideShow();

  /* -------------------- Trailer Modal -------------------- */
  const trailerModal = document.getElementById('trailerModal');
  const modalTrailer = document.getElementById('modalTrailer');
  const closeModalBtn = trailerModal.querySelector('.close-modal');

  // Show trailer modal and load video URL (You may trigger this from watch buttons if implemented)
  // For demonstration, let's assume "Watch Now" buttons open trailer modal with the embedded video.
  document.querySelectorAll('.watch-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const movieId = btn.dataset.id;

      // Fetch trailer URL via API or set video source if available
      // Here, assuming trailer URL embedded in data attribute or you have an API endpoint to fetch trailer
      // For demo, let's use iframe trailer in slider or static URL, or you can adapt this part.

      // Example: Fetch trailer URL dynamically here if you want (AJAX)
      // For now, we'll fake it by extracting from featuredMovies data or URL:
      // You need to pass trailer URL to JS or include data attribute in button with trailer link

      // Example assumes you add data-trailer attribute to button
      const trailerUrl = btn.getAttribute('data-trailer-url');
      if (!trailerUrl) {
        alert('Trailer not available.');
        return;
      }

      modalTrailer.src = trailerUrl;
      trailerModal.style.display = 'block';
      modalTrailer.focus();
    });
  });

  function closeTrailerModal() {
    trailerModal.style.display = 'none';
    modalTrailer.pause();
    modalTrailer.src = '';
  }

  closeModalBtn.addEventListener('click', closeTrailerModal);
  closeModalBtn.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      closeTrailerModal();
    }
  });

  // Close modal on ESC key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && trailerModal.style.display === 'block') {
      closeTrailerModal();
    }
  });

  // Close modal on click outside modal content
  trailerModal.addEventListener('click', e => {
    if (e.target === trailerModal) {
      closeTrailerModal();
    }
  });

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

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
          button.innerHTML = `<i class="fas fa-check"></i> Added`;
          setTimeout(() => {
            button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
            button.disabled = false;
          }, 3000);
        } else {
          alert(data.message || 'Failed to add to watchlist.');
          button.disabled = false;
          button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
        }
      } catch (error) {
        alert('An error occurred. Please try again.');
        button.disabled = false;
        button.innerHTML = `<i class="fas fa-plus"></i> Add to Watchlist`;
        console.error('Watchlist AJAX error:', error);
      }
    });
  });

});