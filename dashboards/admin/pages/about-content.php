<?php
// Include database connection
include '../includes/dbconnexion.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../includes/logout.php');
    exit;
}

// Fetch the current About page data from the database
$query = "SELECT * FROM about_content WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$aboutData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no data is found for About page, show an error
if (!$aboutData) {
    die("Aucune donnée trouvée pour la page 'About'.");
}

// Handle form submission to update the About page content
if (isset($_POST['save'])) {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $paragraph1 = $_POST['paragraph1'];
    $paragraph2 = $_POST['paragraph2'];
    $service1 = $_POST['service1'];
    $service2 = $_POST['service2'];
    $service3 = $_POST['service3'];
    $button_text = $_POST['button_text'];
    $button_link = $_POST['button_link'];

    // Handle image uploads
    $image1 = !empty($_FILES['image1']['name']) ? $_FILES['image1']['name'] : $aboutData['image1'];
    $image2 = !empty($_FILES['image2']['name']) ? $_FILES['image2']['name'] : $aboutData['image2'];

    // Prepare the update query
    $updateQuery = "UPDATE about_content SET 
                    title = :title,
                    subtitle = :subtitle,
                    paragraph1 = :paragraph1,
                    paragraph2 = :paragraph2,
                    service1 = :service1,
                    service2 = :service2,
                    service3 = :service3,
                    button_text = :button_text,
                    button_link = :button_link,
                    image1 = :image1,
                    image2 = :image2
                    WHERE id = 1";

    // Prepare and execute the update query
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':subtitle', $subtitle);
    $stmt->bindParam(':paragraph1', $paragraph1);
    $stmt->bindParam(':paragraph2', $paragraph2);
    $stmt->bindParam(':service1', $service1);
    $stmt->bindParam(':service2', $service2);
    $stmt->bindParam(':service3', $service3);
    $stmt->bindParam(':button_text', $button_text);
    $stmt->bindParam(':button_link', $button_link);
    $stmt->bindParam(':image1', $image1);
    $stmt->bindParam(':image2', $image2);

    // Execute the update query
    if ($stmt->execute()) {
        // Move the uploaded images to the designated folder
        if (!empty($_FILES['image1']['name'])) {
            $target_dir = "../img/about/";
            $target_file = $target_dir . basename($_FILES["image1"]["name"]);
            move_uploaded_file($_FILES["image1"]["tmp_name"], $target_file);
        }

        if (!empty($_FILES['image2']['name'])) {
            $target_dir = "../../../img/about/";
            $target_file = $target_dir . basename($_FILES["image2"]["name"]);
            move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file);
        }

        $message = ["type" => "success", "text" => "Page 'About' mise à jour avec succès."];
        header('location:about-content.php'); // Refresh to show success message
    } else {
        $message = ["type" => "error", "text" => "Erreur lors de la mise à jour de la page 'About'."];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du contenu de la page 'About'</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex sidebar-fixed">
        <?php
        include '../includes/sidebarAd.php'; // Sidebar
        ?>
        <div class="flex-1 p-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
                    <p class="text-gray-600">Gérer le contetnu de la page about</p>
                </div>
        <!-- Main content area -->
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

                <!-- About Update Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <form method="POST" action="about-content.php" enctype="multipart/form-data">
                            <!-- Title and Subtitle -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="title" name="title" value="<?php echo $aboutData['title']; ?>" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-6">
                                <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                                <input type="text" id="subtitle" name="subtitle" value="<?php echo $aboutData['subtitle']; ?>" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Paragraphs -->
                            <div class="mb-6">
                                <label for="paragraph1" class="block text-sm font-medium text-gray-700">Paragraph 1</label>
                                <textarea id="paragraph1" name="paragraph1" rows="4" 
                                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo $aboutData['paragraph1']; ?></textarea>
                            </div>

                            <div class="mb-6">
                                <label for="paragraph2" class="block text-sm font-medium text-gray-700">Paragraph 2</label>
                                <textarea id="paragraph2" name="paragraph2" rows="4" 
                                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo $aboutData['paragraph2']; ?></textarea>
                            </div>

                            <!-- Services -->
                            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="service1" class="block text-sm font-medium text-gray-700">Service 1</label>
                                    <input type="text" id="service1" name="service1" value="<?php echo $aboutData['service1']; ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="service2" class="block text-sm font-medium text-gray-700">Service 2</label>
                                    <input type="text" id="service2" name="service2" value="<?php echo $aboutData['service2']; ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="service3" class="block text-sm font-medium text-gray-700">Service 3</label>
                                    <input type="text" id="service3" name="service3" value="<?php echo $aboutData['service3']; ?>" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Button Text and Link -->
                            <div class="mb-6">
                                <label for="button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                                <input type="text" id="button_text" name="button_text" value="<?php echo $aboutData['button_text']; ?>" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-6">
                                <label for="button_link" class="block text-sm font-medium text-gray-700">Button Link</label>
                                <input type="text" id="button_link" name="button_link" value="<?php echo $aboutData['button_link']; ?>" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Image Uploads -->
                            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="image1" class="block text-sm font-medium text-gray-700">Image 1</label>
                                    <input type="file" id="image1" name="image1" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="image2" class="block text-sm font-medium text-gray-700">Image 2</label>
                                    <input type="file" id="image2" name="image2" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="mb-6">
                                <button type="submit" name="save" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                    Save Changes
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
