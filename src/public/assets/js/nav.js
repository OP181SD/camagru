export function pagination() {
    const navButtons = document.querySelectorAll('.nav-btn[data-view]:not([data-view="back"])');
    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            navButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });
}