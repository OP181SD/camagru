<div class="parents-forgot-password">

    <div class="forgot-container">
        <div class="forgot-header">
            <h4 class="tagline-forgot">
                Réinitialisez votre mot de passe
            </h4>
            <p class="forgot-subtext">
                Entrez votre nouveau mot de passe ci-dessous. 
                Assurez-vous que votre mot de passe est sécurisé.
            </p>
        </div>
        <form class="forgot-for-reset-password">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            
            <input type="password" name="new_password" placeholder="Nouveau mot de passe" required autocomplete="new-password">
            <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required autocomplete="new-password">

            <button type="submit" class="submit-btn-forgot-password">Changer le mot de passe</button>
            <button type="button" class="submit-btn-forgot-password" onclick="location.href='/login'">Retour</button>
        </form>
    </div>
</div