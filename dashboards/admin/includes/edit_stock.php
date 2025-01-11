<?php
// Include the database connection file
include('db2.php'); // Adjust the path if needed

// Establish the connection
$pdo = connect(); // Get the PDO instance from the connect function

// Check if the connection was successful
if (!$pdo) {
    die("Échec de la connexion : " . $pdo->errorInfo());
}

// Get the medication ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : ''; // Change to $_POST['id'] if form submission

// Fetch the existing details for the medication from the database
if ($id) {
    $sql = "SELECT * FROM stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $medication = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle the form submission to update the medication details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $quantity = $_POST['quantity'];  // Only updating quantity

    // Update the medication quantity in the database
    $sql = "UPDATE stock SET quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Quantité mise à jour avec succès !";
        header("Location: stock.php"); // Redirect back to the stock page
        exit();
    } else {
        echo "Erreur lors de la mise à jour de la quantité.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Médicament</title>
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

        <!-- Main Content -->
        <div class="main-content">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier Médicament</h1>

            <!-- Form for Editing Medication -->
            <form method="POST" class="space-y-6" action="edit_stock.php?id=<?php echo $id; ?>">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" value="<?php echo htmlspecialchars($medication['name']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité</label>
                    <input type="number" name="quantity" value="<?php echo $medication['quantity']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="text" value="<?php echo $medication['price']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>

                <div>
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                    <input type="date" value="<?php echo $medication['expiration_date']; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>

                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                    <input type="text" value="<?php echo htmlspecialchars($medication['supplier']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>

                <div>
                    <label for="added_on" class="block text-sm font-medium text-gray-700">Ajouté le</label>
                    <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i', strtotime($medication['added_on'])); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Mettre à jour la quantité</button>
                    <a href="stock.php" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Retour au stock</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
