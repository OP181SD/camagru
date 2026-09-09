<div class="parents-feed">

    <nav>
        <span class="publish-feed">Publication</span>
        <span class="studio-feed" onclick="location.href='/studio'">Studio</span>
        <div class="p-1"></div>
        <hr class="hr-description-section">
    </nav>



    <?php if (!empty($publications) && is_array($publications)): ?>
        <?php foreach ($publications as $publication):
            $pubId = $publication['id'];
            $nbComments = !empty($publication['comments']) ? count($publication['comments']) : 0; ?>

            <div class="publication-container">
                <div class="parents-username-profil-picture">
                    <img
                        class="image-profil"
                        src="<?= htmlspecialchars($publication['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>"
                        alt="photo-profil">
                    <span class="username-image-profil">
                        <?= htmlspecialchars($publication['username'] ?? 'Utilisateur non trouvé') ?>
                    </span>

                    <?php if ($_SESSION['user']['id'] === $publication['user_id']): ?>

                        <div class="more-options">
                            <img class="options-publication" src="/assets/svg/more.svg" alt="options"
                                data-publication-id="<?= $publication['id'] ?>">
                        </div>
                        <div class="modal-more-options">
                            <div class="modal-content-more-options">
                                <div class="close-option">Fermer</div>
                                <div class="delete-option">Supprimer</div>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>

    
                <div class="mt-10px">
                    <img
                        class="image-publication"
                        src="<?= htmlspecialchars($publication['image_path']) ?>"
                        alt="photo publication">
                </div>


                <div class="comments-and-likes-section">

                    <div class="comments-section" data-publication-id="<?= $publication['id'] ?>">
                        <img class="like-publication" src="/assets/svg/heart.svg" alt="like">
                        <img class="liked-publication" src="/assets/svg/heart-filled.png" alt="like">
                        <img
                            class="comment-publication"
                            src="/assets/svg/comment.svg"
                            alt="comment"
                            data-id="<?= $publication['id'] ?? $post['id'] ?>">

                        <div class="share-container">
                            <img class="share-publication"
                                src="/assets/svg/share.svg"
                                alt="Partager"
                                data-image="<?= htmlspecialchars($publication['image_path']) ?>">

                            <i class="fab fa-twitter share-twitter"
                                data-image="<?= htmlspecialchars($publication['image_path']) ?>"
                                style="display: none;"></i>

                            <i class="fab fa-facebook-f share-facebook"
                                data-image="<?= htmlspecialchars($publication['image_path']) ?>"
                                style="display: none;"></i>

                            <i class="fas fa-times share-reset"
                                style="display: none; color: black; cursor: pointer;"></i>
                        </div>
                    </div>

                    <span class="number-likes"><?= htmlspecialchars($publication['likes'] ?? 0) ?> j'aime</span>


                    <span id="comments-count-<?= $pubId ?>" class="display-comments" data-id="<?= $pubId ?>">
                        <?= $nbComments > 0 ? "Voir les $nbComments commentaires" . ($nbComments > 1 ? "" : "") : "Voir les commentaires" ?>
                    </span>

                    <div class="add-comment-wrapper">
                        <input
                            id="comment-input-<?= $pubId ?>"
                            data-publication-id="<?= $pubId ?>"
                            name="comment"
                            class="add-comment-input"
                            type="text"
                            placeholder="Ajouter un commentaire...">
                        <button class="post-comment-button">Publier</button>
                    </div>

                    <div class="time-section">
                        <span class="time-feed-publish">
                            il y a <?= htmlspecialchars(timeAgoFR($publication['created_at'] ?? date('Y-m-d H:i:s'))) ?>
                        </span>
                    </div>
                    <hr class="hr-description-section">
                </div>


                <div class="modal-overlay" id="modal-<?= $pubId ?>">

                    <div class="modal-content">
                        <div class="modal-left">
                            <img
                                src="<?= htmlspecialchars($publication['image_path'] ?? '/assets/photos/bisous.jpg') ?>"
                                alt="photo publication"
                                class="image-modal">
                        </div>

                        <div class="modal-right">

                            <div class="modal-commentary">
                                <div class="profil-begin-modal">
                                    <img
                                        class="image-profil"
                                        src="<?= htmlspecialchars($publication['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>"
                                        alt="photo de profil">
                                    <span class="username-image-profil">
                                        <?= htmlspecialchars($publication['username'] ?? 'Utilisateur non trouvé') ?>
                                    </span>
                                </div>

                                <div class="modal-comments">

                                    <?php if (!empty($publication['comments']) && is_array($publication['comments'])): ?>
                                        <?php foreach ($publication['comments'] as $comment): ?>
                                            <div class="comment">
                                                <img
                                                    class="comment-profile"
                                                    src="<?= htmlspecialchars($comment['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>"
                                                    alt="profil utilisateur">
                                                <div class="comment-content">
                                                    <div class="comment-header">
                                                        <span class="comment-username"><?= htmlspecialchars($comment['username'] ?? 'Utilisateur inconnu') ?></span>

                                                        <span class="comment-time"><?= htmlspecialchars(timeAgoFR($comment['created_at'] ?? date('Y-m-d H:i:s'))) ?></span>
                                                    </div>
                                                    <span class="comment-text"><?= htmlspecialchars($comment['content'] ?? '') ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <div class="no-comments">Aucun commentaire pour le moment</div>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="parents-none-publication">
            <div class="container-feed">
                <p class="no-photos-feed">Aucune publication pour le moment.</p>
            </div>
        </div>
    <?php endif; ?>
</div>