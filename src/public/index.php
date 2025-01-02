<?php
    session_start();
    require_once("../backend/database.php");

    $single_connexion = Database::getInstance();
    $pdoConnection = $single_connexion->getConnection();

    try {
        $stmt = $pdoConnection->query('SELECT 1');
        echo "Connexion réussie à la base de données.";
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
?>
