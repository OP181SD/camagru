<div class="settings-wrapper">

    <div class="settings-sidebar">
        <ul class="settings-menu">
            <li class="menu-item active">Profil</li>
            <li class="menu-item">Sécurité</li>
        </ul>
    </div>

    <div class="settings-content">
        <form id="settings-form">
            <h1 class="section-title" style="text-align: center;">Modifier vos Informations</h1>

            <div class="settings-section" id="profil">

                <div class="profil-item">
                    <label for="username-input" class="profile-label">Nom d'utilisateur</label>
                    <input
                        type="text"
                        id="username-input"
                        name="username"
                        class="input-action username-input"
                        placeholder="Entrez votre nouveau nom d'utilisateur"
                        autocomplete="username">
                    <button type="button" class="btn-action btn-edit" id="btn-edit-username">Modifier le nom d'utilisateur</button>
                </div>


                <div class="profil-item">
                    <label for="email-input" class="profile-label">Email</label>
                    <input
                        type="email"
                        id="email-input"
                        name="email"
                        class="input-action email-input"
                        placeholder="Entrez votre nouvelle email"
                        autocomplete="email">
                    <button type="button" class="btn-action btn-edit" id="btn-edit-email">Modifier l'email</button>
                </div>

            </div>

            <div class="settings-section hidden" id="security">

                <div class="profil-item">
                    <label for="new-password-input" class="profile-label">Nouveau mot de passe</label>
                    <input
                        type="password"
                        id="new-password-input"
                        name="new_password"
                        class="input-action password-input"
                        placeholder="Entrez votre nouveau mot de passe"
                        autocomplete="new-password">

                    <label for="confirm-password-input" class="profile-label">Confirmez votre mot de passe</label>
                    <input
                        type="password"
                        id="confirm-password-input"
                        name="confirm_password"
                        class="input-action confirm-password-input"
                        placeholder="Confirmez votre mot de passe"
                        autocomplete="new-password">
                    <button type="button" class="btn-action btn-edit" id="btn-edit-password">Modifier le mot de passe</button>
                </div>

            </div>

            <div class="profil-item">
                <label for="notify_comments" class="profile-label">
                    Recevoir un email lorsqu’un utilisateur commente votre image :
                </label>

                <div class="toggle-wrapper" style="display:flex;align-items:center;gap:10px;">
                    <label class="switch">
                        <input
                            type="checkbox"
                            id="notify_comments"
                            name="notify_comments"
                            <?= ($_SESSION['notify_comments'] ?? 1) ? 'checked' : '' ?>
                            data-user-id="<?= $_SESSION['user']['id'] ?? 0 ?>">
                        <span class="slider round"></span>
                    </label>
                </div>

                <button type="button" class="btn-action btn-edit" id="btn-edit-notification">
                    Sauvegarder la préférence
                </button>
            </div>

        </form>
    </div>
</div>
</div>
</div>