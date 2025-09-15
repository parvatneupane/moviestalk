  
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const sidebar = document.querySelector(".sidebar");

            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });

            // Add confirmation for delete actions
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const userName = e.target.closest('tr').querySelector('td:first-child div:nth-child(2)').textContent;
                    if (confirm(`Are you sure you want to delete ${userName}? This action cannot be undone.`)) {
                        // Here you would typically make an API call to delete the user
                        alert(`${userName} has been deleted successfully.`);
                    }
                });
            });

            // Add functionality for edit buttons
            const editButtons = document.querySelectorAll('.btn-edit');
            editButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const userName = e.target.closest('tr').querySelector('td:first-child div:nth-child(2)').textContent;
                    alert(`Edit form for ${userName} would open here.`);
                });
            });
        });
   