import { displayErrorsGeneric } from "./utils.js";

 const handleLogin = async (form) => {
        const loginData = new FormData(form);
        const data = Object.fromEntries(loginData.entries());
        const fieldError = displayErrorsGeneric(form);

        try {
            const response = await fetch("/login-auth", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.status === "success") {
                window.location.href = "/feed";
            } else {
                fieldError.textContent = Object.values(result.message);
                form.appendChild(fieldError);
            }
        } catch (error) {
            alert("Erreur sur le login");
        }
};

export const login = (form) => {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        handleLogin(form);
    });
};