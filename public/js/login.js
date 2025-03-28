document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("forms");
    const inputs = form.querySelectorAll("input");

    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });

    // Detectar cambios en los inputs para quitar los mensajes de error cuando sean válidos
    inputs.forEach(input => {
        input.addEventListener("input", function () {
            if (input.checkValidity()) {
                input.classList.remove("is-invalid");
                input.classList.add("is-valid");
            } else {
                input.classList.remove("is-valid");
                input.classList.add("is-invalid");
            }
        });
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
