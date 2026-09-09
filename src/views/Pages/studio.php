<div class="content-studio">

    <div class="editor-main">

        <div class="parents-open-camera">
            <h2 class="take-a-pic">Prendre une photo</h2>
            <button id="open-camera" class="open-camera-button">Allumer</button>
            <button id="close-camera" class="close-camera-button">Éteindre</button>
        </div>

        <hr class="hr-editor">

        <div class="camera-preview">
            <video id="webcam" autoplay playsinline></video>
            <canvas id="snapshot-canvas"></canvas>
            <img id="sticker" />
        </div>


        <h2 class="take-a-pic">Importer une image</h2>
        <hr class="hr-editor">

        <div class="parents-upload-section">
            <form id="upload-form" class="upload-section">
                <input type="file" name="user-image" id="file-upload" accept="image/*" style="display: none;">
                <label for="file-upload" class="custom-file-btn">Choisir un fichier</label>
                <button type="button" id="cancel-upload" class="delete-btn">Annuler</button>
                <button type="submit" id="publish-upload" class="publish-btn">Publier</button>
            </form>
        </div>


        <div class="overlay-section">
            <p class="overlay-instruction">Choisissez un sticker à superposer :</p>

            <div class="overlay-right">
                <label class="overlay-label">
                    <input type="radio" name="overlay" value="tree">
                    <img class="overlay-img" src="/assets/stickers/tree.png" alt="Sticker arbre" data-src="/assets/stickers/tree.png">
                </label>

                <label class="overlay-label">
                    <input type="radio" name="overlay" value="tree-two">
                    <img class="overlay-img" src="/assets/stickers/tree-two.png" alt="Sticker arbre double" data-src="/assets/stickers/tree-two.png">
                </label>

                <label class="overlay-label">
                    <input type="radio" name="overlay" value="none">
                    <span class="stickers-font" aria-label="Aucun sticker">Aucun sticker</span>
                </label>
            </div>
        </div>

        <div class="button-capture-parents">
            <button class="capture-btn-button" id="capture-btn">Prendre la photo</button>
        </div>
    </div>
    <aside class="editor-sidebar">
        <h2 class="my-pictures"> Vos photos</h2>
        <div class="gallery">
        </div>
    </aside>
</div>
</div>