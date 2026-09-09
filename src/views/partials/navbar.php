<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>

<?php if (!empty($isConnected)): ?>
    
    <div class="page-wrapper">

        <header class="header-navigation">
            <div class="parents-nav">
                <h3 class="logo-nav">Camagru</h3>
                    <nav class="nav-links">
                        <?php if ($currentPath !== '/feed'): ?>
                            <a href="/feed" class="nav-link home">Accueil</a>
                        <?php endif; ?>
                        <a href="/settings" class="nav-link settings">Paramètres</a>
                        <form action="/logout" method="POST" class="nav-link-form"">
                            <button type="submit" class="nav-link logout-btn">Déconnexion</button>
                        </form>
                    </nav>
            </div>
        </header>
    <?php endif; ?>