<?php
// dbconnexion.php

$host = 'localhost'; // Adresse du serveur
$dbname = 'clinic-elmassira'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur (par défaut c'est souvent 'root' pour XAMPP)
$password = ''; // Laissez vide si vous n'avez pas de mot de passe pour l'utilisateur 'root'

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
