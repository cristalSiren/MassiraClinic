<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Vérifier si un ID d'élément de carrousel a été passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $carousel_id = $_GET['id'];

    // Supprimer l'élément du carrousel de la base de données
    $query = "DELETE FROM carousel_content WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $carousel_id);

    // Exécuter la suppression et vérifier le résultat
    if ($stmt->execute()) {
        // Rediriger avec un message de succès
        header('Location: carousel-content.php?message=deleted');
        exit();
    } else {
        // Rediriger avec un message d'erreur en cas d'échec
        header('Location: carousel-content.php?message=error');
        exit();
    }
} else {
    // Rediriger si aucun ID valide n'est passé
    header('Location: carousel-content.php');
    exit();
}
