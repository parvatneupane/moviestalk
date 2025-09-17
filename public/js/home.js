document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.video-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevArrow = document.querySelector('.arrow.prev');
    const nextArrow = document.querySelector('.arrow.next');
    let currentIndex = 0;
    let slideInterval;

    function showSlide(index) {
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
        showSlide(nextIndex);
    }

    function prevSlide() {
        let prevIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(prevIndex);
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            resetInterval();
        });
        dot.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                showSlide(index);
                resetInterval();
            }
        });
    });

    nextArrow.addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });

    prevArrow.addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });

    prevArrow.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            prevSlide();
            resetInterval();
        }
    });

    nextArrow.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            nextSlide();
            resetInterval();
        }
    });

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 8000); // 8 sec interval
    }

    // Initialize
    showSlide(currentIndex);
    slideInterval = setInterval(nextSlide, 8000);
});
