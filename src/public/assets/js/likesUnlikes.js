const toggleLikes = async (liked, pubId, likesNumber, emptyHeart, filledHeart) => {
    try {
        const response = await fetch('/likes-count', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ publication_id: pubId, liked })
        });

        const result = await response.json();

        if (result.status === 'success') {
            likesNumber.textContent = `${result.likes} j'aime`;
            emptyHeart.style.display = liked ? 'none' : 'inline';
            filledHeart.style.display = liked ? 'inline' : 'none';
        }
    } catch (err) {
        
    }
};


export function likesUnlikes() {
    const likesParents = document.querySelectorAll('.comments-section');
    if (likesParents.length === 0) return;

    likesParents.forEach(async (section) => {
        const emptyHeart = section.querySelector('.like-publication');
        const filledHeart = section.querySelector('.liked-publication');
        const likesNumber = section.parentElement.querySelector('.number-likes');
        const pubId = section.dataset.publicationId;
        if (!emptyHeart || !filledHeart || !likesNumber || !pubId) return;


        let isLiked = false;
        try {
            const response = await fetch(`/likes-status?publication_id=${pubId}`);
            const result = await response.json();
            if (result.status === 'success') {
                isLiked = result.liked;
                likesNumber.textContent = `${result.likes} j'aime`;
            }
        } catch (err) {
          
        }

        emptyHeart.style.display = isLiked ? 'none' : 'inline';
        filledHeart.style.display = isLiked ? 'inline' : 'none';

        emptyHeart.addEventListener('click', () => toggleLikes(true, pubId, likesNumber, emptyHeart, filledHeart));
        filledHeart.addEventListener('click', () => toggleLikes(false, pubId, likesNumber, emptyHeart, filledHeart));
    });
}