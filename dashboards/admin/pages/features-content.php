<?php
require_once '../includes/dbconnexion.php';

// Initialiser `$features` comme tableau vide
$features = [];
$successMessage = '';
$errorMessage = '';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['subtitle'], $_POST['description'])) {
    try {
        // Récupérer les données du formulaire
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        // Préparer la requête d'insertion
        $query = "INSERT INTO features_content (title, subtitle, description) VALUES (:title, :subtitle, :description)";
        $stmt = $conn->prepare($query);

        // Lier les valeurs aux paramètres
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':subtitle', $subtitle, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Message de succès
        $successMessage = "Fonctionnalité ajoutée avec succès !";
        header("Location: features-content.php?success=" . urlencode($successMessage));
        exit();  // Assurez-vous que le script s'arrête après la redirection
    } catch (PDOException $e) {
        // Message d'erreur en cas de problème avec la base de données
        $errorMessage = "Erreur de la base de données : " . $e->getMessage();
    }
}

// Récupérer les fonctionnalités pour affichage
try {
    // Récupérer les valeurs de recherche et de pagination
    $searchTitle = filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRING) ?? '';
    $searchSubtitle = filter_input(INPUT_GET, 'subtitle', FILTER_SANITIZE_STRING) ?? '';
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    // Préparer les paramètres de la requête de recherche
    $params = [];
    $whereConditions = [];

    // Ajouter les conditions de recherche uniquement si les champs sont remplis
    if (!empty($searchTitle)) {
        $whereConditions[] = "title LIKE :title";
        $params[':title'] = "%{$searchTitle}%";
    }
    if (!empty($searchSubtitle)) {
        $whereConditions[] = "subtitle LIKE :subtitle";
        $params[':subtitle'] = "%{$searchSubtitle}%";
    }

    // Générer la clause WHERE si des critères de recherche sont fournis
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

    // Préparer la requête de récupération des données avec pagination
    $query = "SELECT * FROM features_content {$whereClause} LIMIT :offset, :perPage";
    $stmt = $conn->prepare($query);

    // Lier les paramètres de pagination
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

    // Lier les autres paramètres de recherche si disponibles
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    $stmt->execute();

    // Récupérer les résultats
    $features = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la requête pour compter le nombre total de fonctionnalités
    $countQuery = "SELECT COUNT(*) FROM features_content {$whereClause}";
    $countStmt = $conn->prepare($countQuery);

    // Lier les paramètres de recherche pour la requête de comptage
    foreach ($params as $param => $value) {
        $countStmt->bindValue($param, $value);
    }

    $countStmt->execute();
    $totalFeatures = $countStmt->fetchColumn();

} catch (PDOException $e) {
    // Afficher l'erreur exacte de la base de données
    echo "Erreur de la base de données : " . $e->getMessage();
    exit; // Arrêter l'exécution pour analyser l'erreur
} catch (Exception $e) {
    // Afficher toute autre erreur
    echo "Erreur générale : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des fonctionnalités</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col lg:flex-row h-screen">
        <!-- Sidebar -->
        <aside class="bg-white shadow-lg w-full lg:w-1/4 p-4 lg:p-6">
            <?php include '../includes/sidebarAd.php'; ?>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-auto">
            <div class="container mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des fonctionnalités</h1>
                    <button onclick="toggleModal()" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>

                <!-- Affichage du message de succès ou d'erreur -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="bg-green-500 text-white p-4 rounded-md mb-6">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php elseif ($errorMessage): ?>
                    <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <!-- Search form -->
                <form method="GET" action="" class="bg-white p-4 rounded-md shadow-md mb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="title" class="block text-sm">Titre</label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($searchTitle); ?>" class="w-full border rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label for="subtitle" class="block text-sm">Sous-titre</label>
                            <input type="text" name="subtitle" id="subtitle" value="<?php echo htmlspecialchars($searchSubtitle); ?>" class="w-full border rounded-md px-3 py-2">
                        </div>
                        <div class="col-span-2 flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Rechercher</button>
                        </div>
                    </div>
                </form>

                <!-- Add Feature Modal -->
                <div id="addFeatureModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                        <h2 class="text-xl font-bold mb-4">Ajouter une fonctionnalité</h2>
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label for="title" class="block text-sm">Titre</label>
                                <input type="text" name="title" class="w-full border rounded-md px-3 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label for="subtitle" class="block text-sm">Sous-titre</label>
                                <input type="text" name="subtitle" class="w-full border rounded-md px-3 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm">Description</label>
                                <textarea name="description" class="w-full border rounded-md px-3 py-2" required></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="toggleModal()" class="bg-gray-300 px-4 py-2 rounded-md">Annuler</button>
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Features Table -->
                <div class="bg-white rounded-lg shadow-md">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-3">Titre</th>
                                <th class="p-3">Sous-titre</th>
                                <th class="p-3">Description</th>
                                <th class="p-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($features): ?>
                                <?php foreach ($features as $feature): ?>
                                    <tr class="border-b">
                                        <td class="p-3"><?php echo htmlspecialchars($feature['title']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($feature['subtitle']); ?></td>
                                        <td class="p-3"><?php echo htmlspecialchars($feature['description']); ?></td>
                                        <td class="px-4 py-2">
                                                <!-- Icône pour Modifier -->
                                                <a href="edit-feature.php?id=<?php echo $feature['id']; ?>" class="text-blue-500">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Icône pour Supprimer -->
                                                <a href="delete-feature.php?id=<?php echo $feature['id']; ?>" class="text-red-500 ml-2">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="p-3 text-center">Aucune fonctionnalité trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById("addFeatureModal");
            modal.classList.toggle("hidden");
        }
    </script>
</body>
</html>
