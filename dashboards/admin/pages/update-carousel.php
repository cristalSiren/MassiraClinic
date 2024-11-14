<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Initialisation des variables pour le message et l'ID de l'élément du carrousel
$message = null;

// Vérifier si un ID de carrousel a été passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $carousel_id = $_GET['id'];

    // Récupérer les informations de l'élément du carrousel
    $query = "SELECT * FROM carousel_content WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $carousel_id);
    $stmt->execute();
    $carouselItem = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'élément du carrousel existe
    if (!$carouselItem) {
        $message = ["type" => "error", "text" => "Élément du carrousel introuvable."];
    }
} else {
    // Rediriger si aucun ID valide n'est passé
    header('location:carousel-content.php');
    exit();
}

// Traiter la soumission du formulaire de mise à jour
if (isset($_POST['update'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $button_text = htmlspecialchars($_POST['button_text']);
    $button_link = htmlspecialchars($_POST['button_link']);
    $image = !empty($_FILES['image']['name']) ? $_FILES['image']['name'] : $carouselItem['image'];

    // Vérifier si une nouvelle image a été téléchargée
    if (!empty($_FILES['image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            $target_dir = "../../../img/carousel/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            
            // Vérifier si le fichier existe déjà
            if (file_exists($target_file)) {
                $message = ["type" => "error", "text" => "L'image existe déjà. Veuillez renommer le fichier et réessayer."];
            } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Image téléchargée avec succès
                $image = basename($_FILES["image"]["name"]);
            } else {
                $message = ["type" => "error", "text" => "Erreur lors du téléchargement de l'image."];
            }
        } else {
            $message = ["type" => "error", "text" => "Seuls les fichiers image (JPEG, PNG, GIF) sont autorisés."];
        }
    }

    // Mettre à jour les informations dans la base de données
    if (!$message) { // Pas d'erreurs d'image
        $updateQuery = "UPDATE carousel_content 
                        SET title = :title, description = :description, button_text = :button_text, button_link = :button_link, image = :image 
                        WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':button_text', $button_text);
        $stmt->bindParam(':button_link', $button_link);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $carousel_id);
        
        if ($stmt->execute()) {
            $message = ["type" => "success", "text" => "Élément du carrousel mis à jour avec succès."];
            header('location:carousel-content.php');
            exit();
        } else {
            $message = ["type" => "error", "text" => "Erreur lors de la mise à jour de l'élément du carrousel."];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour le contenu du carrousel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex">
        <?php include '../includes/sidebarAd.php'; ?>

        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Mettre à jour le contenu du carrousel</h1>

            <!-- Afficher le message de succès ou d'erreur -->
            <?php if ($message): ?>
                <div class="p-4 mb-4 text-white bg-<?php echo $message['type'] === 'error' ? 'red' : 'green'; ?>-500 rounded">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de mise à jour du contenu du carrousel -->
            <form method="POST" action="update_carousel.php?id=<?php echo $carousel_id; ?>" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($carouselItem['title']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required><?php echo htmlspecialchars($carouselItem['description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="button_text" class="block text-sm font-medium text-gray-700">Texte du bouton</label>
                    <input type="text" id="button_text" name="button_text" value="<?php echo htmlspecialchars($carouselItem['button_text']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="button_link" class="block text-sm font-medium text-gray-700">Lien du bouton</label>
                    <input type="text" id="button_link" name="button_link" value="<?php echo htmlspecialchars($carouselItem['button_link']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Image actuelle</label>
                    <img src="../../../img/carousel/<?php echo $carouselItem['image']; ?>" alt="Image du carrousel" class="w-32 h-32 object-cover mb-2">
                    <input type="file" id="image" name="image" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-2">Télécharger une nouvelle image pour remplacer l'image actuelle.</p>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Mettre à jour</button>
                    <a href="carousel-content.php" class="ml-4 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
