export function toggleElementsState(disabled, ...elements) {
    elements.forEach(element => {
        if (element) {
            element.style.opacity = disabled ? 0.5 : 1;
            element.style.cursor = disabled ? 'not-allowed' : 'pointer';
            element.style.pointerEvents = disabled ? 'none' : 'auto';
        }
    });
}

export function toggleUploadButtons(disabled) {

    const fileInput = document.getElementById('file-upload');
    const fileLabel = document.querySelector('label[for="file-upload"]');
    const publishBtn = document.getElementById('publish-upload');
    const cancelBtn = document.getElementById('cancel-upload');

    [fileInput, fileLabel, publishBtn, cancelBtn].forEach(el => {
        if (!el) return;

        if (el.tagName === 'INPUT' || el.tagName === 'BUTTON') {
            el.disabled = disabled;
        }
        
        el.style.cursor = disabled ? 'not-allowed' : 'pointer';
        el.style.opacity = disabled ? '0.5' : '1';
        el.style.pointerEvents = disabled ? 'none' : 'auto';
    });
}

export function displayErrorsGeneric(form) {

    form.querySelectorAll(".field-error").forEach(element => element.remove());
    const fieldError = document.createElement('div');
    fieldError.classList.add('field-error');
    return fieldError;
}

export function displaySuccessGeneric(form) {
    form.querySelectorAll(".field-success").forEach(element => element.remove());
    const fieldSuccess = document.createElement('div');
    fieldSuccess.classList.add('field-success');
    return fieldSuccess;
}