<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Variable de message pour afficher les erreurs ou les succès
$message = null;

// Récupérer toutes les données du carrousel depuis la base de données
$query = "SELECT * FROM carousel_content";
$stmt = $conn->prepare($query);
$stmt->execute();
$carouselData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gérer la soumission du formulaire pour ajouter un nouveau contenu au carrousel
if (isset($_POST['save_new'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $button_text = htmlspecialchars($_POST['button_text']);
    $button_link = htmlspecialchars($_POST['button_link']);
    $image = !empty($_FILES['image']['name']) ? $_FILES['image']['name'] : null;

    if ($image) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            $target_dir = "../../../img/carousel/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            // Vérifier si le fichier existe déjà pour éviter d'écraser
            if (file_exists($target_file)) {
                $message = ["type" => "error", "text" => "L'image existe déjà. Veuillez renommer le fichier et réessayer."];
            } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insérer le nouveau contenu du carrousel dans la base de données
                $insertQuery = "INSERT INTO carousel_content (title, description, button_text, button_link, image) 
                                VALUES (:title, :description, :button_text, :button_link, :image)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':button_text', $button_text);
                $stmt->bindParam(':button_link', $button_link);
                $stmt->bindParam(':image', $image);
                
                if ($stmt->execute()) {
                    $message = ["type" => "success", "text" => "Nouveau contenu ajouté avec succès au carrousel."];
                    header('location:carousel-content.php');
                    exit();
                } else {
                    $message = ["type" => "error", "text" => "Erreur lors de l'ajout du contenu au carrousel."];
                }
            } else {
                $message = ["type" => "error", "text" => "Erreur lors du téléchargement de l'image."];
            }
        } else {
            $message = ["type" => "error", "text" => "Seuls les fichiers image (JPEG, PNG, GIF) sont autorisés."];
        }
    } else {
        $message = ["type" => "error", "text" => "Veuillez télécharger une image pour le carrousel."];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du contenu du carrousel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex">
        <?php include '../includes/sidebarAd.php'; ?>

        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Gestion du contenu du carrousel</h1>

            <!-- Afficher un message d'erreur ou de succès si présent -->
            <?php if ($message): ?>
                <div class="p-4 mb-4 text-white bg-<?php echo $message['type'] === 'error' ? 'red' : 'green'; ?>-500 rounded">
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>

            <!-- Tableau du contenu du carrousel -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 border-b bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 border-b bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Texte du bouton</th>
                            <th class="px-6 py-3 border-b bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lien du bouton</th>
                            <th class="px-6 py-3 border-b bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 border-b bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carouselData as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap border-b text-gray-700"><?php echo $item['title']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap border-b text-gray-700"><?php echo $item['description']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap border-b text-gray-700"><?php echo $item['button_text']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap border-b text-gray-700"><?php echo $item['button_link']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap border-b">
                                <img src="../../../img/carousel/<?php echo $item['image']; ?>" alt="Image du carrousel" class="w-16 h-16 object-cover">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <!-- Icône pour Modifier -->
                                <a href="update-carousel.php?id=<?php echo $item['id']; ?>" class="text-blue-500">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Icône pour Supprimer -->
                                <a href="delete-carousel.php?id=<?php echo $item['id']; ?>" class="text-red-500 ml-2">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bouton pour Ajouter un Nouveau Contenu -->
            <div class="mt-6">
                <button id="addCarouselBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Ajouter du contenu au carrousel</button>
            </div>

            <!-- Modal pour Ajouter du Contenu -->
            <div id="addCarouselModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
                    <h2 class="text-2xl font-bold mb-4">Ajouter du contenu au carrousel</h2>
                    <form method="POST" action="carousel-content.php" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" id="title" name="title" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="button_text" class="block text-sm font-medium text-gray-700">Texte du bouton</label>
                            <input type="text" id="button_text" name="button_text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="button_link" class="block text-sm font-medium text-gray-700">Lien du bouton</label>
                            <input type="text" id="button_link" name="button_link" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" id="image" name="image" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button type="submit" name="save_new" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Enregistrer</button>
                            <button type="button" id="cancelAddCarousel" class="ml-4 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Affiche la modal pour l'ajout de contenu
        document.getElementById("addCarouselBtn").onclick = function() {
            document.getElementById("addCarouselModal").classList.remove("hidden");
        };
        
        // Masque la modal quand le bouton Annuler est cliqué
        document.getElementById("cancelAddCarousel").onclick = function() {
            document.getElementById("addCarouselModal").classList.add("hidden");
        };
    </script>
</body>
</html>
