<?php
// Include database connection
include '../includes/dbconnexion.php';

// Fetch the current footer data from the database
$query = "SELECT * FROM footer_info WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$footerData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no data is found for footer, show error
if (!$footerData) {
    die("Aucune donnée trouvée pour le footer.");
}

// Handle form submission to update the footer information
if (isset($_POST['save'])) {
    // Sanitize form inputs
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $facebook = filter_var($_POST['facebook'], FILTER_SANITIZE_URL);
    $twitter = filter_var($_POST['twitter'], FILTER_SANITIZE_URL);
    $linkedin = filter_var($_POST['linkedin'], FILTER_SANITIZE_URL);
    $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_URL);

    // Validate URLs
    $validUrls = true;
    if (!filter_var($facebook, FILTER_VALIDATE_URL) && !empty($facebook)) $validUrls = false;
    if (!filter_var($twitter, FILTER_VALIDATE_URL) && !empty($twitter)) $validUrls = false;
    if (!filter_var($linkedin, FILTER_VALIDATE_URL) && !empty($linkedin)) $validUrls = false;
    if (!filter_var($instagram, FILTER_VALIDATE_URL) && !empty($instagram)) $validUrls = false;

    if ($validUrls) {
        // Prepare update query
        $updateQuery = "UPDATE footer_info SET 
                        address = :address,
                        phone = :phone,
                        facebook_link = :facebook,
                        twitter_link = :twitter,
                        linkedin_link = :linkedin,
                        instagram_link = :instagram 
                        WHERE id = 1";

        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':twitter', $twitter);
        $stmt->bindParam(':linkedin', $linkedin);
        $stmt->bindParam(':instagram', $instagram);

        if ($stmt->execute()) {
            $message = ["type" => "success", "text" => "Informations du footer mises à jour avec succès."];
            header('location:footer-content.php');
        } else {
            $message = ["type" => "error", "text" => "Erreur lors de la mise à jour des informations."];
        }
    } else {
        $message = ["type" => "error", "text" => "Veuillez entrer des liens URL valides."];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Footer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex">
        <?php 
        include '../includes/header.php';
        include '../includes/sidebarAd.php'; 
        ?>

        <!-- Main content area -->
        <div class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <!-- Footer Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Configuration du Footer</h1>
                            <p class="text-gray-600 mt-2">Gérez les informations de contact et les liens sociaux</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-600"><i class="fas fa-info-circle mr-2"></i>Ces informations seront affichées dans le pied de page du site</p>
                        </div>
                    </div>
                </div>

                <?php if (isset($message)): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $message['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?>">
                    <div class="flex items-center">
                        <i class="fas <?php echo $message['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-3"></i>
                        <?php echo $message['text']; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Main Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <form method="POST" action="footer-content.php">
                            <!-- Contact Information Section -->
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                                    <i class="fas fa-building mr-2 text-blue-500"></i>
                                    Informations de Contact
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>Adresse
                                        </label>
                                        <input type="text" id="address" name="address" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['address']); ?>" required>
                                    </div>
                                    <div class="space-y-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-phone mr-2 text-gray-400"></i>Téléphone
                                        </label>
                                        <input type="text" id="phone" name="phone" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['phone']); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media Section -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                                    <i class="fas fa-share-alt mr-2 text-blue-500"></i>
                                    Réseaux Sociaux
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label for="facebook" class="block text-sm font-medium text-gray-700">
                                            <i class="fab fa-facebook mr-2 text-[#1877f2]"></i>Facebook
                                        </label>
                                        <input type="url" id="facebook" name="facebook" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['facebook_link']); ?>">
                                    </div>
                                    <div class="space-y-4">
                                        <label for="twitter" class="block text-sm font-medium text-gray-700">
                                            <i class="fab fa-twitter mr-2 text-[#1da1f2]"></i>Twitter
                                        </label>
                                        <input type="url" id="twitter" name="twitter" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['twitter_link']); ?>">
                                    </div>
                                    <div class="space-y-4">
                                        <label for="linkedin" class="block text-sm font-medium text-gray-700">
                                            <i class="fab fa-linkedin mr-2 text-[#0077b5]"></i>LinkedIn
                                        </label>
                                        <input type="url" id="linkedin" name="linkedin" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['linkedin_link']); ?>">
                                    </div>
                                    <div class="space-y-4">
                                        <label for="instagram" class="block text-sm font-medium text-gray-700">
                                            <i class="fab fa-instagram mr-2 text-[#e4405f]"></i>Instagram
                                        </label>
                                        <input type="url" id="instagram" name="instagram" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                            value="<?php echo htmlspecialchars($footerData['instagram_link']); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit" name="save" class="px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>Sauvegarder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
