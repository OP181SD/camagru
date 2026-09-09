import { handleStickerSelection, handleCaptureGestion } from "./stickers.js";
import { handleThumbnails } from "./handleThumbnails.js";
import { moveStickers } from "./stickersMovement.js";
import { stickerPosition } from './stickersMovement.js';
import { toggleUploadButtons } from '../utils.js';

export let stream = null;
let selectedOverlay = null;

export function getSelectedOverlay() {
    return selectedOverlay;
}

export function setSelectedOverlay(sticker) {
    selectedOverlay = sticker;
}

async function getMediaDevices(webcam) {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        webcam.srcObject = stream;
    } catch (error) {
        alert("Erreur sur lors de l'ouverture de la camera");
    }
}

export function takePhoto(webcam) {
    const captureBtn = document.getElementById('capture-btn');
    const gallery = document.querySelector('.gallery');

    captureBtn.addEventListener('click', () => {
        const canvas = document.getElementById('snapshot-canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = webcam.videoWidth;
        canvas.height = webcam.videoHeight;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(webcam, 0, 0, canvas.width, canvas.height);

        const stickerSrc = getSelectedOverlay();
        if (stickerSrc) {
            const stickerImg = new Image();
            stickerImg.src = stickerSrc;
            stickerImg.onload = () => {
                ctx.drawImage(
                    stickerImg,
                    stickerPosition.x,
                    stickerPosition.y,
                    stickerImg.width,
                    stickerImg.height
                );

                const imageDataUrl = canvas.toDataURL('image/png');
                handleThumbnails(imageDataUrl, gallery, getSelectedOverlay());
            };
        }
    });
}


function handleCloseCamera(closeCameraBtn, openCameraBtn, webcam, stickerElement, captureBtn, stickerPreview) {

    closeCameraBtn.addEventListener('click', async () => {

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            webcam.srcObject = null;
            stream = null;
            stickerElement.style.display = 'none';
        }
        closeCameraBtn.style.display = 'none';
        openCameraBtn.style.display = 'inline-block';
        if (!getSelectedOverlay()) {
            captureBtn.disabled = true;
            captureBtn.style.opacity = 0.5;
            captureBtn.style.cursor = 'not-allowed';
        }
        toggleUploadButtons(false);
        stickerPreview.style.pointerEvents = 'none';
        stickerPreview.style.opacity = '0.5';

        captureBtn.disabled = true;
        captureBtn.style.opacity = 0.5;
        captureBtn.style.cursor = 'not-allowed';
    });
}

export function openCamera() {

    const openCameraBtn = document.getElementById('open-camera');
    const closeCameraBtn = document.getElementById('close-camera');
    const webcam = document.getElementById('webcam');
    const stickerElement = document.getElementById('sticker');
    const captureBtn = document.getElementById('capture-btn');
    const cameraPreview = document.querySelector('.camera-preview');
    const stickerPreview = document.querySelector('.overlay-section');
    if (!openCameraBtn || !closeCameraBtn || !webcam || !stickerElement || !captureBtn || !cameraPreview || !stickerPreview)
        return;

    captureBtn.disabled = true;
    captureBtn.style.opacity = 0.5;
    captureBtn.style.cursor = 'not-allowed';

    stickerPreview.style.pointerEvents = 'none';
    stickerPreview.style.opacity = '0.5';


    openCameraBtn.addEventListener('click', async () => {
        await getMediaDevices(webcam);
        toggleUploadButtons(true);

        openCameraBtn.style.display = 'none';
        closeCameraBtn.style.display = 'inline-block';

        stickerPreview.style.pointerEvents = 'auto';
        stickerPreview.style.opacity = '1';
        stickerPreview.style.display = 'flex';

        handleCaptureGestion(captureBtn);
        moveStickers(stickerElement, cameraPreview);
        takePhoto(webcam);
    });
    handleCloseCamera(closeCameraBtn, openCameraBtn, webcam, stickerElement, captureBtn, stickerPreview);
    handleStickerSelection(stickerElement, captureBtn);
}