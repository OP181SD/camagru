const parentsSwitch = (showParents, hideParents, activeBtn, inactiveBtn) =>
{
    showParents.classList.remove('hidden');
    hideParents.classList.add('hidden');
    activeBtn.classList.add('active');
    inactiveBtn.classList.remove('active');

}
export function settings() {

    const getProfilSection = document.getElementById('profil');
    const getSecuritySection = document.getElementById('security');

    const getProfilList = document.querySelector('.menu-item.active');
    const getSecurityList = document.querySelector('.menu-item:last-child');

    if (!getProfilSection || !getSecuritySection || !getProfilList || !getSecurityList) {
        return;
    }


    getProfilList.addEventListener('click', () => {
parentsSwitch(getProfilSection,
            getSecuritySection,
            getProfilList,
            getSecurityList);
    });

    getSecurityList.addEventListener('click', () => {
        parentsSwitch(
            getSecuritySection,
            getProfilSection,
            getSecurityList,
            getProfilList
        );
    });

    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        const profilItem = button.closest('.profil-item');

        let fieldError = profilItem.querySelector('.field-error');
        if (!fieldError) {
            fieldError = document.createElement('div');
            fieldError.classList.add('field-error');
            button.insertAdjacentElement('afterend', fieldError);
        }

        button.addEventListener('click', async (e) => {
            e.preventDefault();

            if (button.disabled) return;

            let payload = {};

            const inputs = profilItem.querySelectorAll('input');
            inputs.forEach(input => {
                payload[input.name] = input.value;
            });

            const checkbox = profilItem.querySelector('input[name="notify_comments"]');
            if (checkbox) {
                payload['notify_comments'] = checkbox.checked ? 1 : 0;
            }

            try {
                button.disabled = true;

                const response = await fetch('/settings/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'include'
                });

                const result = await response.json();

                if (result.status === "success") {
                    fieldError.textContent = "Modification enregistrée !";
                    fieldError.style.color = "#367144";

                    setTimeout(() => {
                        fieldError.textContent = "";
                    }, 3000);
                } else {
                    fieldError.textContent = Object.values(result.message);
                    fieldError.style.color = "#d93025";
                }

            } catch (error) {
                alert("Erreur sur le settings");
            } finally {
                button.disabled = false;
            }
        });
    });
}