
function publishButtonInterruptor(addComment, publishButton) {

    if (!addComment || !publishButton) return;

    const togglePublishButtonVisibility  = () => {
        if (addComment.value.trim().length > 0)
            publishButton.style.display = 'inline-block';
        else
            publishButton.style.display = 'none';
    }
    addComment.addEventListener('input', togglePublishButtonVisibility);
}

export function commentForPublishing() {

    const wrappers = document.querySelectorAll('.add-comment-wrapper');
    if (wrappers.length === 0) return;

    wrappers.forEach(wrapper => {

        const addComment = wrapper.querySelector('.add-comment-input');
        const publishButton = wrapper.querySelector('.post-comment-button');

        publishButtonInterruptor(addComment,publishButton);
        const handleClick = async () => {

            const content = addComment.value.trim();
            const pubId = addComment.dataset.publicationId;
            if (!content) return;

            try {
                const response = await fetch('/commentary-published', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        publication_id: pubId,
                        content: content
                    })
                });
                const result = await response.json();
                if (result.status === 'success') {
                    const commentCount = document.getElementById(`comments-count-${pubId}`);
                    if (commentCount) {
                        commentCount.textContent = `Voir les ${result.comments_count} commentaires`;
                    }
                    addComment.value = "";
                    publishButton.style.display = 'none';
                }
            } catch (error) {
                alert("Une erreur est survenue lors de la publication du commentaire.");
            }
        }
        publishButton.addEventListener('click', handleClick);
    });
}