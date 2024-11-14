<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Récupérer les données actuelles de la page Contact depuis la base de données
$query = "SELECT * FROM contact_content WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);  // Utiliser $conn au lieu de $pdo
$stmt->execute();
$contactData = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si les données sont trouvées
if (!$contactData) {
    echo "Aucune donnée trouvée pour la page 'Contact'.";
    exit;
}

// Gérer la soumission du formulaire pour mettre à jour le contenu de la page Contact
if (isset($_POST['save'])) {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $map = $_POST['map'];
    $social1 = $_POST['social1'];
    $social2 = $_POST['social2'];
    $social3 = $_POST['social3'];

    // Préparer la requête de mise à jour
    $updateQuery = "UPDATE contact_content SET 
                    address = :address,
                    phone = :phone,
                    email = :email,
                    map = :map,
                    social1 = :social1,
                    social2 = :social2,
                    social3 = :social3
                    WHERE id = 1";

    // Préparer et exécuter la requête de mise à jour
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':map', $map);
    $stmt->bindParam(':social1', $social1);
    $stmt->bindParam(':social2', $social2);
    $stmt->bindParam(':social3', $social3);

    // Exécuter la requête de mise à jour
    if ($stmt->execute()) {
        $message = ["type" => "success", "text" => "Page 'Contact' mise à jour avec succès."];
        header('location:contact-content.php'); // Actualiser pour afficher le message de succès
    } else {
        $message = ["type" => "error", "text" => "Erreur lors de la mise à jour de la page 'Contact'."];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du contenu de la page 'Contact'</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex">
        <?php
        include '../includes/sidebarAd.php'; // Sidebar
        ?>
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
                <p class="text-gray-600">Gérer le contenu de la page Contact</p>
            </div>
            <!-- Zone de contenu principal -->
            <div class="flex-1 p-8">
                <div class="max-w-4xl mx-auto">
                    <?php if (isset($message)): ?>
                        <div class="mb-6 p-4 rounded-lg <?php echo $message['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?>">
                            <div class="flex items-center">
                                <i class="fas <?php echo $message['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-3"></i>
                                <?php echo $message['text']; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de mise à jour du contenu Contact -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6">
                            <form method="POST" action="contact-content.php">
                                <!-- Adresse -->
                                <div class="mb-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($contactData['address']); ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Téléphone -->
                                <div class="mb-6">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($contactData['phone']); ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Email -->
                                <div class="mb-6">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($contactData['email']); ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Lien Google Map -->
                                <div class="mb-6">
                                    <label for="map" class="block text-sm font-medium text-gray-700">Lien Google Map</label>
                                    <input type="text" id="map" name="map" value="<?php echo htmlspecialchars($contactData['map']); ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Liens des réseaux sociaux -->
                                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="social1" class="block text-sm font-medium text-gray-700">Réseau social 1</label>
                                        <input type="text" id="social1" name="social1" value="<?php echo htmlspecialchars($contactData['social1']); ?>" 
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="social2" class="block text-sm font-medium text-gray-700">Réseau social 2</label>
                                        <input type="text" id="social2" name="social2" value="<?php echo htmlspecialchars($contactData['social2']); ?>" 
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="social3" class="block text-sm font-medium text-gray-700">Réseau social 3</label>
                                        <input type="text" id="social3" name="social3" value="<?php echo htmlspecialchars($contactData['social3']); ?>" 
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <button type="submit" name="save" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
