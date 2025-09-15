// register.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    // Optional: Basic client-side validation (HTML already has required attributes)
    form.addEventListener("submit", function (e) {
        const password = document.getElementById("password");
        const confirm = document.getElementById("password_confirmation");

        if (password.value !== confirm.value) {
            e.preventDefault();
            alert("Passwords do not match!");
            confirm.focus();
        }
    });

    // Optional: Add password toggle feature
    const toggleIcons = document.querySelectorAll('.input-group i.fa-lock');

    toggleIcons.forEach(icon => {
        icon.style.cursor = 'pointer';
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-lock');
                icon.classList.add('fa-unlock');
            } else {
                input.type = "password";
                icon.classList.remove('fa-unlock');
                icon.classList.add('fa-lock');
            }
        });
    });
});
