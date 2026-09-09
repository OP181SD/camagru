export function sharePublication(imageUrl, network) {

    if (imageUrl.startsWith("/")) {
        imageUrl = window.location.origin + imageUrl;
    }

    const encodedUrl = encodeURIComponent(imageUrl);
    const text = encodeURIComponent("Regardez cette image !");
    let url = "";

    if (network === "twitter") {
        url = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${text}`;
    } else if (network === "facebook") {
        url = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
    }
    return url;
}

export function initShareButtons() {
    const shareContainers = document.querySelectorAll(".share-container");

    shareContainers.forEach(container => {
        const avion = container.querySelector(".share-publication");
        const twitter = container.querySelector(".share-twitter");
        const facebook = container.querySelector(".share-facebook");
        const reset = container.querySelector(".share-reset");

        if (!avion || !twitter || !facebook || !reset) {
            return;
        }


        avion.addEventListener("click", () => {
            avion.style.display = "none";
            twitter.style.display = "inline-block";
            facebook.style.display = "inline-block";
            reset.style.display = "inline-block";
        });

        reset.addEventListener("click", () => {
            avion.style.display = "inline-block";
            twitter.style.display = "none";
            facebook.style.display = "none";
            reset.style.display = "none";
        });

        twitter.addEventListener("click", () => {
            const imageUrl = twitter.dataset.image;
            const url = sharePublication(imageUrl, "twitter");
            window.open(url, "Partager sur Twitter", "width=600,height=400");
        });

        facebook.addEventListener("click", () => {
            const imageUrl = facebook.dataset.image;
            const url = sharePublication(imageUrl, "facebook");
            window.open(url, "Partager sur Facebook", "width=600,height=400");
        });
    });
}