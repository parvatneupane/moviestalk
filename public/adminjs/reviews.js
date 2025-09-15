
            document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const sidebar = document.querySelector(".sidebar");
            const deleteButtons = document.querySelectorAll('.btn-delete');
            const editButtons = document.querySelectorAll('.btn-edit');
            const viewButtons = document.querySelectorAll('.btn-view');
            const approveButtons = document.querySelectorAll('.btn-approve');
            const modal = document.getElementById("reviewDetailModal");
            const closeModal = document.querySelector(".close");
            const closeModalBtn = document.getElementById("modal-close");
            const exportBtn = document.getElementById("export-reviews");

            // Sidebar toggle
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });

            // Delete button functionality
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const userName = e.target.closest('tr').querySelector('.user-info div:nth-child(2)').textContent;
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(1) div:nth-child(1)').textContent;
                    if (confirm(`Are you sure you want to delete ${userName}'s review of "${movieTitle}"?`)) {
                        // Here you would typically make an API call to delete the review
                        alert(`Review by ${userName} for "${movieTitle}" has been deleted successfully.`);
                    }
                });
            });
            
            // Edit button functionality
            editButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const userName = e.target.closest('tr').querySelector('.user-info div:nth-child(2)').textContent;
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(1) div:nth-child(1)').textContent;
                    alert(`Edit form for ${userName}'s review of "${movieTitle}" would open here.`);
                });
            });
            
            // Approve button functionality
            approveButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const userName = e.target.closest('tr').querySelector('.user-info div:nth-child(2)').textContent;
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(1) div:nth-child(1)').textContent;
                    if (confirm(`Approve ${userName}'s review of "${movieTitle}"?`)) {
                        // Here you would typically make an API call to approve the review
                        alert(`Review by ${userName} for "${movieTitle}" has been approved and published.`);
                    }
                });
            });
            
            // View button functionality - opens modal
            viewButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const reviewId = button.getAttribute('data-review-id');
                    const row = e.target.closest('tr');
                    const movieTitle = row.querySelector('td:nth-child(1) div:nth-child(1)').textContent;
                    const director = row.querySelector('td:nth-child(1) div:nth-child(2)').textContent;
                    const userName = row.querySelector('.user-info div:nth-child(2)').textContent;
                    const rating = row.querySelector('.rating-badge').textContent.trim();
                    const reviewText = row.querySelector('.review-text').textContent;
                    const date = row.querySelector('td:nth-child(5)').textContent;
                    const status = row.querySelector('.status-badge').textContent;
                    const statusClass = row.querySelector('.status-badge').classList[1];
                    
                    // Populate modal with data
                    document.getElementById('modal-movie-title').textContent = `${movieTitle} (${director})`;
                    document.getElementById('modal-user-info').textContent = `By ${userName}`;
                    document.getElementById('modal-rating').innerHTML = `<i class="fas fa-star"></i> ${rating}`;
                    document.getElementById('modal-date').textContent = `Posted on: ${date}`;
                    document.getElementById('modal-status').textContent = status;
                    document.getElementById('modal-status').className = `status-badge ${statusClass}`;
                    
                    // For demo purposes, using a longer review text
                    const fullReviews = {
                        1: "Mind-bending masterpiece! Nolan's direction is impeccable, and the visual effects are stunning. The concept of dream within a dream was executed perfectly. The cast delivered outstanding performances, especially Leonardo DiCaprio. The score by Hans Zimmer elevated every scene. This is one of those films that gets better with each viewing as you discover new details.",
                        2: "Heath Ledger's Joker is the best villain performance I've ever seen. This movie sets the bar for superhero films. Christian Bale is perfect as Batman/Bruce Wayne. The action sequences are thrilling, and the storyline is engaging from start to finish. A true cinematic achievement that transcends its genre.",
                        3: "A classic that truly deserves its reputation. The performances are outstanding, especially Brando and Pacino. The storytelling is masterful, and the cinematography captures the mood perfectly. This film influenced countless movies that came after it. A must-watch for any film enthusiast.",
                        4: "Tom Hanks delivers an incredible performance, but the film's historical revisionism bothers me. While emotionally powerful and well-crafted, it simplifies complex historical events a bit too much for my taste. Still, it's an enjoyable film with heart.",
                        5: "Overrated and confusing. The plot is too complicated and the characters lack depth. I found it hard to connect with any of them. The constant shifting between dream layers became tedious rather than exciting. Not Nolan's best work in my opinion."
                    };
                    
                    document.getElementById('modal-review-text').textContent = fullReviews[reviewId] || reviewText;
                    
                    // Show modal
                    modal.style.display = "flex";
                });
            });
            
            // Close modal
            closeModal.addEventListener('click', () => {
                modal.style.display = "none";
            });
            
            closeModalBtn.addEventListener('click', () => {
                modal.style.display = "none";
            });
            
            // Close modal if clicked outside
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });
            
            // Export reviews functionality
            exportBtn.addEventListener('click', () => {
                alert('Reviews exported successfully! This would download a CSV file with all review data.');
            });
            
            // Filter functionality
            const filters = document.querySelectorAll('.filter-select');
            filters.forEach(filter => {
                filter.addEventListener('change', () => {
                    alert(`Filtering by: ${filter.value}. In a real application, this would filter the reviews table.`);
                });
            });
        });
  