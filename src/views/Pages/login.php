<div class="login-parents-container">

    <div class="login-container">

        <div class="login-header">
            <h4 class="tagline-login">
                Connectez-vous pour voir les photos de vos amis.
            </h4>
        </div>

        <form class="login-form" data-endpoint="/login-auth">
            <input type="text" class="input-login" name="username_or_email" placeholder="Nom d'utilisateur ou e-mail" required autocomplete="current-email">
            <input type="password" class="input-login" name="password" placeholder="Mot de passe" required autocomplete="current-password">
            <button type="submit" class="submit-btn-login">Se connecter</button>
        </form>

        <div class="separator">
            <div class="line"></div>
            <div class="or">OU</div>
            <div class="line"></div>
        </div>

        <div class="forgot-password">
            <a href="/forgot-password">Mot de passe oublié ?</a>
        </div>

        <div class="back-home">
            <a href="/">Retour</a>
        </div>
    </div>
    
    <div class="login-signup-container">
        <p>Vous n'avez pas de compte ? <a class="signup-link" href="/signup">Inscrivez-vous</a></p>
    </div>

</div>