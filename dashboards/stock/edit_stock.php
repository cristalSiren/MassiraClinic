<?php
// Inclure le fichier de connexion à la base de données
include('db2.php'); // Ajustez le chemin si nécessaire

// Établir la connexion
$pdo = connect(); // Obtenir l'instance PDO depuis la fonction connect

// Vérifier si la connexion a réussi
if (!$pdo) {
    die("Échec de la connexion : " . $pdo->errorInfo());
}

// Récupérer l'ID du médicament depuis l'URL (ou POST si modification)
$id = isset($_GET['id']) ? $_GET['id'] : ''; // Changez en $_POST['id'] si soumission du formulaire

// Récupérer les détails existants du médicament depuis la base de données
if ($id) {
    $sql = "SELECT * FROM stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $medication = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traiter la soumission du formulaire pour mettre à jour les détails du médicament
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecter les données du formulaire
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiration_date = $_POST['expiration_date'];
    $supplier = $_POST['supplier'];
    $added_on = $_POST['added_on'];

    // Mettre à jour les détails du médicament dans la base de données
    $sql = "UPDATE stock SET name = :name, quantity = :quantity, price = :price, expiration_date = :expiration_date, supplier = :supplier, added_on = :added_on WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':expiration_date', $expiration_date);
    $stmt->bindParam(':supplier', $supplier);
    $stmt->bindParam(':added_on', $added_on);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Médicament mis à jour avec succès !";
        header("Location: stock.php"); // Rediriger vers la page des stocks
        exit();
    } else {
        echo "Erreur lors de la mise à jour du médicament.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Médicament</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .content-container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 16rem;
            color: white;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #f7fafc;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include 'sidebarAd.php'; ?>
        </div>

        <!-- Contenu Principal -->
        <div class="main-content">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier le Médicament</h1>

            <!-- Formulaire pour Modifier le Médicament -->
            <form method="POST" class="space-y-6" action="edit_stock.php?id=<?php echo $id; ?>">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($medication['name']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité</label>
                    <input type="number" name="quantity" value="<?php echo $medication['quantity']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="text" name="price" value="<?php echo $medication['price']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Date d'Expiration</label>
                    <input type="date" name="expiration_date" value="<?php echo $medication['expiration_date']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                    <input type="text" name="supplier" value="<?php echo htmlspecialchars($medication['supplier']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="added_on" class="block text-sm font-medium text-gray-700">Ajouté le</label>
                    <input type="datetime-local" name="added_on" value="<?php echo date('Y-m-d\TH:i', strtotime($medication['added_on'])); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Mettre à Jour le Médicament</button>
                    <a href="stock.php" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Retour au Stock</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
