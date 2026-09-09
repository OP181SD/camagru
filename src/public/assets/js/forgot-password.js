import { displayErrorsGeneric, displaySuccessGeneric } from "./utils.js";

export function forgotPasswordHandler() {
    const form = document.querySelector('.send-email-formular');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const dataToSend = Object.fromEntries(formData.entries());
        const fieldError = displayErrorsGeneric(form);

        form.querySelectorAll('.field-error, .field-success').forEach(el => el.remove());

        try {
            const response = await fetch('/send-mail', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });

            const result = await response.json();

            if (result.status === 'success') {
                const successMessage = displaySuccessGeneric(form);
                successMessage.textContent = result.message;
                form.appendChild(successMessage);
            } else if (result.status === 'errors') {
                fieldError.textContent = Object.values(result.message);
                form.appendChild(fieldError);
            }
        } catch (error) {
           alert("Erreur sur le send-email veuillez réessayer.");
        }
    });
}

export function resetPasswordHandler() {
    const form = document.querySelector('.forgot-for-reset-password');
    if (!form) return;


    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const dataToSend = Object.fromEntries(formData.entries());


        form.querySelectorAll('.field-error, .field-success').forEach(el => el.remove());

        try {
            const response = await fetch('/reset-forgot-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSend)
            });

            const result = await response.json();


            if (result.status === 'success') {
                const successMessage = displaySuccessGeneric(form);
                successMessage.textContent = result.message;
                form.appendChild(successMessage);
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1000);
            } else if (result.status === 'errors') {
                const fieldError = displayErrorsGeneric(form);
                fieldError.textContent = Object.values(result.message);
                form.appendChild(fieldError);
            }
        } catch (error) {
            alert("Une erreur est survenue lors du reset du mot de passe");
        }
    });
}