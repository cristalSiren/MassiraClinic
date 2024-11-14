<?php
require_once '../includes/dbconnexion.php';

if (isset($_GET['id'])) {
    $featureId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($featureId) {
        try {
            // Préparer la requête de suppression
            $query = "DELETE FROM features_content WHERE id = :id";
            $stmt = $conn->prepare($query);

            // Lier l'ID à la requête
            $stmt->bindValue(':id', $featureId, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Rediriger vers la page de gestion avec un message de succès
            header("Location: features-content.php?success=" . urlencode("Fonctionnalité supprimée avec succès !"));
            exit();
        } catch (PDOException $e) {
            // Message d'erreur en cas de problème avec la base de données
            echo "Erreur de suppression : " . $e->getMessage();
            exit;
        }
    } else {
        // Si l'ID est invalide
        echo "ID de fonctionnalité invalide.";
        exit;
    }
} else {
    // Si l'ID n'est pas présent dans l'URL
    echo "Aucun ID de fonctionnalité spécifié.";
    exit;
}
?>
