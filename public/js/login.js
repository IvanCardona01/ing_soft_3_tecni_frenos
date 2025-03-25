document.addEventListener("DOMContentLoaded", function () {
    // Validación de Bootstrap
    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        }, false);
    });

    // Mostrar/Ocultar Contraseña
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                passwordInput.type = "password";
                togglePassword.classList.replace("bi-eye-slash", "bi-eye");
            }
        });
    }
});
