<div class="signup-parents-container">

    <div class="signup-container">

        <div class="signup-header">
            <h4 class="tagline-signup">
                Inscrivez-vous pour voir les photos de vos amis.
            </h4>
        </div>

        <div class="separator">
            <div class="line"></div>
            <div class="or">OU</div>
            <div class="line"></div>
        </div>
        
        <form class="signup-form" data-endpoint="/register">

            <input type="text" class="input-signup" name="username" placeholder="Nom d'utilisateur" required autocomplete="username">

            <input type="email" class="input-signup" name="email" placeholder="Adresse e-mail" required autocomplete="email">

            <input type="password" class="input-signup" name="password" placeholder="Mot de passe" required autocomplete="new-password">

            <input type="password" class="input-signup" name="confirm_password" placeholder="Confirmez le mot de passe" required autocomplete="new-password">
            
            <button type="submit" class="submit-btn-signup">Suivant</button>
        </form>
    </div>
    <div class="signup-login-container">
        <p>Vous avez déjà un compte ? <a class="signup-link" href="/login">Connectez-vous</a></p>
        <p class="back-home"><a href="/">Retour</a></p>
    </div>
</div>