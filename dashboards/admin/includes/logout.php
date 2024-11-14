<?php
// logout.php

// Démarrer la session
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion (index.php)
header("Location: ../loginAd.php");
exit();
?>
