import { displayErrorsGeneric } from './utils.js'

const handleRegister = async (form) => {
    const signupData = new FormData(form);
    const data = Object.fromEntries(signupData.entries());
    const fieldError = displayErrorsGeneric(form);

    try {
        const response = await fetch("/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.status === "success" && result.data) {
            localStorage.setItem("user_id", result.data.user_id);
            window.location.href = result.data.url;
        } else {
            fieldError.innerHTML = '';
            Object.values(result.message).forEach(msg => {
                const p = document.createElement('p');
                p.textContent = msg;
                p.style.marginBottom = "12px";
                fieldError.appendChild(p);
            });
            form.appendChild(fieldError);
        }
    } catch (error) {
       alert("Erreur sur le handleRegister")
    }
};

export const register = (form) => {

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        handleRegister(form);
    });
};