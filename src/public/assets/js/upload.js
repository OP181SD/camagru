import { toggleElementsState, displaySuccessGeneric, displayErrorsGeneric } from './utils.js';

let selectedFile = null;

const handleSelectedFile = (event, cameraPreview, publishBtn, openCameraBtn, overlaySection) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Veuillez sélectionner une image valide.');
        return;
    }

    selectedFile = file;

    const previewImg = document.createElement('img');
    previewImg.src = URL.createObjectURL(file);
    previewImg.classList.add('uploaded-preview');

    cameraPreview.appendChild(previewImg);

    toggleElementsState(false, publishBtn);
    toggleElementsState(true, openCameraBtn, overlaySection);
};

const uploadPublication = async (form) => {
    if (!selectedFile) {
        const errorMessage = displayErrorsGeneric(form);
        errorMessage.textContent = "Aucune image n’a été sélectionnée.";
        form.appendChild(errorMessage);
        return;
    }

    const removeOldMessages = (form) => {
        form.querySelectorAll('.form-message').forEach(msg => msg.remove());
    };

    const formData = new FormData();
    formData.append('image', selectedFile);

    try {
        const response = await fetch('/upload-publication-pictures', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();
        removeOldMessages(form);
        if (result.status === "success") {
            const msg = displaySuccessGeneric(form);
            msg.textContent = "Publication créée avec succès";
            msg.classList.add('form-message');
            form.appendChild(msg);
            return;

        } else if (result.status === "errors") {
            const msg = displayErrorsGeneric(form);
            msg.textContent = Object.values(result.message).join("\n");
            msg.classList.add('form-message');
            form.appendChild(msg);
            return;
        }
    } catch (err) { 
        alert("Erreur sur l'upload de publication");
    }
};


export function handleFileUpload() {
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('file-upload');
    const cameraPreview = document.querySelector('.camera-preview');
    const publishBtn = document.getElementById('publish-upload');
    const cancelBtn = document.getElementById('cancel-upload');
    const openCameraBtn = document.getElementById('open-camera');
    const overlaySection = document.querySelector('.overlay-section');

    if (!form || !fileInput || !publishBtn || !cancelBtn) return;

    fileInput.addEventListener('change', (e) =>
        handleSelectedFile(e, cameraPreview, publishBtn, openCameraBtn, overlaySection)
    );

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await uploadPublication(form);
    });

    cancelBtn.addEventListener('click', () => {
        const uploadedPreview = cameraPreview.querySelector('.uploaded-preview');
        if (uploadedPreview) uploadedPreview.remove();

        toggleElementsState(false, openCameraBtn);
        toggleElementsState(false, publishBtn);

        selectedFile = null;
        fileInput.value = "";
    });
}
