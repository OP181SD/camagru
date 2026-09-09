<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <script type="module" src="<?= BASE_URL ?>/assets/js/index.js" defer></script>
    
</head>

<body>
    <?php
    $isConnected = !empty($_SESSION['user']);
    $this->renderPartial('navbar', ['isConnected' => $isConnected]);
    ?>
    <main class="main-content">
        <?= $content ?>
    </main>

    <?php $this->renderPartial('footer'); ?>
</body>

</html>