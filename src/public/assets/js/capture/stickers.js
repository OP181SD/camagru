import { stream, getSelectedOverlay, setSelectedOverlay } from './camera.js';

export function handleCaptureGestion(captureBtn) {

    if (stream && getSelectedOverlay()) { 
        captureBtn.disabled = false;
        captureBtn.style.opacity = 1;
        captureBtn.style.cursor = 'pointer';
    } else {
        captureBtn.disabled = true;
        captureBtn.style.opacity = 0.5;
        captureBtn.style.cursor = 'not-allowed';
    }
}


export function handleStickerSelection(stickerElement, captureBtn) {

    const overlayInputs = document.querySelectorAll('.overlay-section input[name="overlay"]');

    overlayInputs.forEach(input => {
        input.addEventListener('change', () => {
            const labelOverlay = input.closest('.overlay-label');
            const imgOverlay = labelOverlay.querySelector('.overlay-img');

            if (imgOverlay) {
                stickerElement.src = imgOverlay.dataset.src;
                stickerElement.style.display = 'block';
                setSelectedOverlay(imgOverlay.dataset.src); 
            } else {
                stickerElement.style.display = 'none';
                setSelectedOverlay(null);
            }
            handleCaptureGestion(captureBtn);
        });
    });
}