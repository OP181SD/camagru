let isDragging = false;
let offsetX = 0;
let offsetY = 0;

export let stickerPosition = { x: 0, y: 0 };

export function moveStickers(stickerElement, cameraPreview) {


    stickerElement.addEventListener('mousedown', (e) => {
        isDragging = true;
        stickerElement.style.cursor = 'grabbing';
        offsetX = e.clientX - stickerElement.offsetLeft;
        offsetY = e.clientY - stickerElement.offsetTop;
    });

    stickerElement.addEventListener('mouseup', () => {
        isDragging = false;
        stickerElement.style.cursor = 'grab';
    });

    document.addEventListener('mousemove', (e) => {

        if (!isDragging) return;

        let newPosX;
        let newPosY;


        newPosX = e.clientX - offsetX;
        newPosY = e.clientY - offsetY;

        let maxX = cameraPreview.offsetWidth - stickerElement.offsetWidth;
    
        let maxY = cameraPreview.offsetHeight - stickerElement.offsetHeight;
  
        let MinX = Math.min(newPosX, maxX);
        let MinY = Math.min(newPosY, maxY);

        newPosX = Math.max(0, MinX);
        newPosY = Math.max(0, MinY);

        stickerElement.style.left = newPosX + "px";
        stickerElement.style.top = newPosY + "px";
        stickerElement.style.cursor = 'grabbing';

        stickerPosition.x = newPosX;
        stickerPosition.y = newPosY;
    });
}