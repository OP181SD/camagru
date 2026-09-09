<?php
$postsPerPage = 5;
$totalPosts = count($publications);
$totalPages = ceil($totalPosts / $postsPerPage);

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;

$startIndex = ($currentPage - 1) * $postsPerPage;
$paginatedPosts = array_slice($publications, $startIndex, $postsPerPage);
?>

<div class="wrapper-gallery">
    <div class="header-gallery">
        <div class="gallery-nav">
            <button class="nav-btn" data-view="back" onclick="window.location.href='/'">
                <svg class="nav-btn-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 12 L12 3 L21 12 V21 H3 Z" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="nav-btn-label">Retour</span>
            </button>

            <button class="nav-btn active" data-view="publications">
                <svg class="nav-btn-icon" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                </svg>
                <span class="nav-btn-label">Publications</span>
            </button>
        </div>
    </div>

    <div class="parents-center">
        <?php if (!empty($paginatedPosts)): ?>
            <div class="gallery-parents">
                <?php foreach ($paginatedPosts as $post):
                    $pubId = $post['id'];
                ?>
                    <div class="post" data-id="<?= $pubId ?>">
                        <div class="post-header">
                            <img class="avatar" src="<?= htmlspecialchars($post['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>" alt="avatar">
                            <span class="username"><?= htmlspecialchars($post['username'] ?? 'Utilisateur inconnu') ?></span>
                        </div>

                        <img class="img-published" src="<?= htmlspecialchars($post['image_path'] ?? '/assets/photos/bisous.jpg') ?>" alt="photo" data-id="<?= $pubId ?>">

                        <div class="post-info">
                            <img class="comment-publication" data-id="<?= $pubId ?>" src="/assets/svg/comment.svg" alt="comment">
                            <span class="likes-count"><?= htmlspecialchars($post['likes'] ?? 0) ?> j'aime</span>
                            <span class="comment-count"><?= !empty($post['comments']) ? count($post['comments']) : 0 ?> commentaires</span>
                            <span class="time-feed-publish">
                                il y a <?= htmlspecialchars(timeAgoFR($post['created_at'] ?? date('Y-m-d H:i:s'))) ?>
                            </span>
                        </div>
                    </div>

                    <div class="modal-overlay" id="modal-<?= $pubId ?>">
                        <div class="modal-content">
                            <div class="modal-left">
                                <img src="<?= htmlspecialchars($post['image_path'] ?? '/assets/photos/bisous.jpg') ?>" class="image-modal" alt="photo publication">
                            </div>
                            <div class="modal-right">
                                <div class="modal-commentary">
                                    <div class="profil-begin-modal">
                                        <img class="image-profil" src="<?= htmlspecialchars($post['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>" alt="profil utilisateur">
                                        <span class="username-image-profil"><?= htmlspecialchars($post['username'] ?? 'Utilisateur inconnu') ?></span>
                                    </div>
                                    <div class="modal-comments">
                                        <?php if (!empty($post['comments']) && is_array($post['comments'])): ?>
                                            <?php foreach ($post['comments'] as $comment): ?>
                                                <div class="comment">
                                                    <img class="comment-profile" src="<?= htmlspecialchars($comment['profile_picture'] ?? '/assets/photos/bisous.jpg') ?>" alt="profil utilisateur">
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
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-posts-wrapper">
                <span class="no-post">Aucune publication pour le moment.</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>" class="pagination-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        <?php endif; ?>

        <span class="pagination-info">Page <?= $currentPage ?> / <?= $totalPages ?></span>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="pagination-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        <?php endif; ?>
    </div>
</div>

