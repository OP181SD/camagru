export function updateComment() {
    const saveBtn = document.getElementById('btn-edit-notification');
    const checkbox = document.getElementById('notify_comments');

    if (!checkbox || !saveBtn) return;

    saveBtn.addEventListener('click', async () => {
        const userId = checkbox.dataset.userId;
        const value = checkbox.checked ? 1 : 0;

        try {
            await fetch('/update-comment-notif', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({
                    user_id: userId,
                    notify_comments: value
                })
            });
        } catch (err) {
           alert("Erreur sur les notificaiton");
        }
    });
}
