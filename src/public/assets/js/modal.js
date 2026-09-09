
export function initCommentModal() {
    const modalOverlays = document.querySelectorAll('.modal-overlay');
    const commentIcons = document.querySelectorAll('.comment-publication');
    const displayComments = document.querySelectorAll('.display-comments');

    commentIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const container = icon.closest('.publication-container, .post');
            const pubId = (container && container.dataset.id) || icon.dataset.id;
            if (!pubId) return;

            const modal = document.getElementById(`modal-${pubId}`);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        });
    });

    displayComments.forEach(el => {
        el.addEventListener('click', () => {
            const pubId = el.dataset.id;
            if (!pubId) return;

            const modal = document.getElementById(`modal-${pubId}`);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        });
    });

    modalOverlays.forEach(modal => {
        modal.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });

        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', e => e.stopPropagation());
        }
    });
}


export function openAndCloseMoreModal(modal) {


    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    const modalContent = modal.querySelector('.modal-content-more-options');
    modalContent.addEventListener('click', e => e.stopPropagation());

    modal.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    });

    const closeBtn = modal.querySelector('.close-option');
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    });
}

export function initMoreOptionsModal() {
    const allOptions = document.querySelectorAll('.options-publication');

    allOptions.forEach(img => {
        const modal = img.closest('.more-options').nextElementSibling;
        const deleteBtn = modal.querySelector('.delete-option');

        deleteBtn.addEventListener('click', async () => {
            const publicationId = img.dataset.publicationId;

            const response = await fetch('/delete-publication-posted', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ publication_id: publicationId })
            });

            const result = await response.json();
            if (result.status === 'success') {
                const publicationElement = img.closest('.publication-container');
                if (publicationElement) publicationElement.remove();
                if (document.querySelectorAll('.publication-container').length === 0) {
                    const parentsFeed = document.querySelector('.parents-feed');
                    parentsFeed.innerHTML += '<div class="parents-none-publication"><div class="container-feed"><p class="no-photos-feed">Aucune publication pour le moment.</p></div></div>';
                }
            } else {
                alert(result.message || 'Erreur lors de la suppression.');
            }
        });

        img.addEventListener('click', () => {
            openAndCloseMoreModal(modal);
        });
    });
}