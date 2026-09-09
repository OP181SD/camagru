import { stickerPosition } from './stickersMovement.js';

export function handleThumbnails(imageDataUrl, gallery, stickerUsed) {


    let thumbnail = document.createElement('div');
    thumbnail.classList.add('thumbnail');

    let img = document.createElement('img');
    img.classList.add('img-thumbnail');
    img.src = imageDataUrl;
    img.alt = "Photo capturée";

    let publishBtn = document.createElement('button');
    publishBtn.classList.add('publish-btn');
    publishBtn.textContent = 'Publier';

    publishBtn.onclick = async function () {
        publishBtn.disabled = true;

        let payload = {
            photo: imageDataUrl,
            sticker: stickerUsed, 
            x: stickerPosition.x,
            y: stickerPosition.y
        };


        try {
            const response = await fetch('/upload-publication-sticker', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
                credentials: 'include'
            });

            const result = await response.json();

            if (response.ok) {
                let successPublish = document.createElement('span');
                successPublish.textContent = "Votre Publication a été enregistrée";
                successPublish.classList.add('publication-message');

                let containerAside = document.querySelector('.editor-sidebar');
                containerAside.appendChild(successPublish);
                setTimeout(() => successPublish.remove(), 2000);
            } else {
                alert("Erreur : " + (result.message || "Impossible de publier"));
            }
        } catch (err) {
            alert("Erreur réseau, veuillez réessayer.");
        }
    };

    let deleteBtn = document.createElement('button');
    deleteBtn.classList.add('delete-btn');
    deleteBtn.textContent = 'Supprimer';
    deleteBtn.addEventListener('click', () => gallery.removeChild(thumbnail));

    thumbnail.appendChild(img);
    thumbnail.appendChild(publishBtn);
    thumbnail.appendChild(deleteBtn);
    gallery.appendChild(thumbnail);
}

