import { displayErrorsGeneric } from "./utils.js";

export function Previsulisation(fileInput, preview) {

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                preview.style.display = "block";
                preview.innerHTML = `<img src="${event.target.result}" alt="Prévisualisation" class="preview-img">`;
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = "none";
            preview.innerHTML = "";
        }
    });
}

export function profilePicture(form) {

    const preview = form.querySelector(".photo-preview");
    const fileInput = form.querySelector('input[type="file"]');

    Previsulisation(fileInput, preview);

    const handleProfilePicture = async () => {

        const formData = new FormData();
        const file = fileInput.files[0];

        const fieldError = displayErrorsGeneric(form);

        if (!file) {
            fieldError.textContent = "Veuillez sélectionner une photo !";
            form.appendChild(fieldError);
            return;
        }

        const user_id = localStorage.getItem("user_id");

        formData.append("profile_picture", file);
        formData.append("user_id", user_id);

        try {
            const response = await fetch("/register-two", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.status === "success") {
                localStorage.removeItem("user_id");
                window.location.href = "/verify-email";
            } else if (result.status === "errors") {
                fieldError.textContent = Object.values(result.message);
                form.appendChild(fieldError);
            }
        } catch (error) {
            fieldError.textContent = "Une erreur est survenue. Veuillez réessayer.";
            form.appendChild(fieldError);
        }
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        handleProfilePicture();
    });

    window.addEventListener("beforeunload", () => {
        const user_id = localStorage.getItem("user_id");
        if (user_id) {
            const formData = new FormData();
            formData.append("user_id", user_id);
            navigator.sendBeacon("/cancel-partial-signup", formData);
        }
    });
}