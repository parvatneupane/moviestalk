 
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.querySelector(".menu-toggle");
            const sidebar = document.querySelector(".sidebar");
            const form = document.getElementById("add-movie-form");
            const posterUpload = document.getElementById("posterUpload");
            const posterInput = document.getElementById("posterInput");

            // Sidebar toggle
            toggle.addEventListener("click", () => {
                sidebar.classList.toggle("active");
            });

            // File upload styling
            posterInput.addEventListener("change", function() {
                if (this.files && this.files[0]) {
                    posterUpload.style.borderColor = "var(--primary)";
                    posterUpload.querySelector(".file-upload-text").textContent = this.files[0].name;
                }
            });

            // Drag and drop for file upload
            posterUpload.addEventListener("dragover", function(e) {
                e.preventDefault();
                this.style.borderColor = "var(--primary)";
                this.style.backgroundColor = "rgba(92, 107, 192, 0.1)";
            });

            posterUpload.addEventListener("dragleave", function() {
                this.style.borderColor = "#ddd";
                this.style.backgroundColor = "transparent";
            });

            posterUpload.addEventListener("drop", function(e) {
                e.preventDefault();
                this.style.borderColor = "var(--primary)";
                this.style.backgroundColor = "transparent";
                
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    posterInput.files = e.dataTransfer.files;
                    posterUpload.querySelector(".file-upload-text").textContent = e.dataTransfer.files[0].name;
                }
            });

            // Form submission
            form.addEventListener("submit", (e) => {
                e.preventDefault();
                
                // Basic validation
                const genreCheckboxes = document.querySelectorAll('input[name="genres"]:checked');
                if (genreCheckboxes.length === 0) {
                    alert("Please select at least one genre.");
                    return;
                }
                
                // In a real application, you would send the data to the server here
                alert("Movie added successfully!");
                form.reset();
                
                // Reset file upload area
                posterUpload.querySelector(".file-upload-text").innerHTML = 'Drag & drop your image here or <span>browse</span>';
                posterUpload.style.borderColor = "#ddd";
            });
        });
    