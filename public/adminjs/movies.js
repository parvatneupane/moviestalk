 
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const sidebar = document.querySelector(".sidebar");
            const deleteButtons = document.querySelectorAll('.btn-delete');
            const editButtons = document.querySelectorAll('.btn-edit');
            const viewButtons = document.querySelectorAll('.btn-view');

            // Sidebar toggle
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });

            // Delete button functionality
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(2) div:nth-child(1)').textContent;
                    if (confirm(`Are you sure you want to delete "${movieTitle}"? This action cannot be undone.`)) {
                        // Here you would typically make an API call to delete the movie
                        alert(`"${movieTitle}" has been deleted successfully.`);
                    }
                });
            });
            
            // Edit button functionality
            editButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(2) div:nth-child(1)').textContent;
                    alert(`Edit form for "${movieTitle}" would open here.`);
                });
            });
            
            // View button functionality
            viewButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const movieTitle = e.target.closest('tr').querySelector('td:nth-child(2) div:nth-child(1)').textContent;
                    alert(`Detail view for "${movieTitle}" would open here.`);
                });
            });
        });
    