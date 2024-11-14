<?php
// Include database connection
include '../includes/dbconnexion.php';

// Fetch the current header data from the database
$query = "SELECT * FROM header_info WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$headerData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no data is found for header, show an error
if (!$headerData) {
    die("Aucune donnée trouvée pour le header.");
}

// Handle form submission to update the header information
if (isset($_POST['save'])) {
    $logo = $_FILES['logo']['name']; // Uploaded logo file name
    $logoActive = isset($_POST['logo_active']) ? 1 : 0; // Logo activation status
    $menuActive = isset($_POST['menu_active']) ? 1 : 0; // Menu activation status
    $contactActive = isset($_POST['contact_active']) ? 1 : 0; // Contact section activation status
    $carouselActive = isset($_POST['carousel_active']) ? 1 : 0; // Carousel section activation status
    $aboutActive = isset($_POST['about_active']) ? 1 : 0; // About section activation status
    $servicesActive = isset($_POST['services_active']) ? 1 : 0; // Services section activation status
    $featuresActive = isset($_POST['features_active']) ? 1 : 0; // Features section activation status
    $teamActive = isset($_POST['team_active']) ? 1 : 0; // Team section activation status
    $appointmentActive = isset($_POST['appointment_active']) ? 1 : 0; // Appointment section activation status
    $testimonialsActive = isset($_POST['testimonials_active']) ? 1 : 0; // Testimonials section activation status

    // Prepare the update query
    $updateQuery = "UPDATE header_info SET 
                    logo_active = :logo_active,
                    menu_active = :menu_active,
                    contact_active = :contact_active,
                    carousel_active = :carousel_active,
                    about_active = :about_active,
                    services_active = :services_active,
                    features_active = :features_active,
                    team_active = :team_active,
                    appointment_active = :appointment_active,
                    testimonials_active = :testimonials_active
                    WHERE id = 1";

    // If logo is uploaded, include it in the update query
    if (!empty($logo)) {
        $updateQuery = "UPDATE header_info SET 
                        logo = :logo,
                        logo_active = :logo_active,
                        menu_active = :menu_active,
                        contact_active = :contact_active,
                        carousel_active = :carousel_active,
                        about_active = :about_active,
                        services_active = :services_active,
                        features_active = :features_active,
                        team_active = :team_active,
                        appointment_active = :appointment_active,
                        testimonials_active = :testimonials_active
                        WHERE id = 1";
    }

    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':logo_active', $logoActive);
    $stmt->bindParam(':menu_active', $menuActive);
    $stmt->bindParam(':contact_active', $contactActive);
    $stmt->bindParam(':carousel_active', $carouselActive);
    $stmt->bindParam(':about_active', $aboutActive);
    $stmt->bindParam(':services_active', $servicesActive);
    $stmt->bindParam(':features_active', $featuresActive);
    $stmt->bindParam(':team_active', $teamActive);
    $stmt->bindParam(':appointment_active', $appointmentActive);
    $stmt->bindParam(':testimonials_active', $testimonialsActive);

    // If logo is uploaded, bind it
    if (!empty($logo)) {
        $stmt->bindParam(':logo', $logo);
    }

    // Execute query and handle file upload
    if ($stmt->execute()) {
        if (!empty($logo)) {
            $target_dir = "../img/logo/";
            $target_file = $target_dir . basename($_FILES["logo"]["name"]);
            move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
        }
        $message = ["type" => "success", "text" => "Header information updated successfully."];
        header('location:header-content.php'); // Refresh to show success message
    } else {
        $message = ["type" => "error", "text" => "Error updating header information."];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du contenu du navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex">
        <?php
        include '../includes/sidebarAd.php'; // Sidebar
        ?>

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

                <!-- Header Update Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <form method="POST" action="header-content.php" enctype="multipart/form-data">
                            <!-- Logo Section -->
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                                    <i class="fas fa-image mr-2 text-blue-500"></i>
                                    Logo
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <label for="logo" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-upload mr-2 text-gray-400"></i>Upload New Logo
                                        </label>
                                        <input type="file" id="logo" name="logo" 
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="space-y-4">
                                        <label for="logo_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-toggle-on mr-2 text-gray-400"></i>Activate Logo
                                        </label>
                                        <input type="checkbox" id="logo_active" name="logo_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['logo_active'] ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                <!-- Display current logo if it exists -->
                                <?php if (!empty($headerData['logo'])): ?>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600">Current logo:</p>
                                        <img src="../img/logo/<?php echo $headerData['logo']; ?>" alt="Current Logo" class="w-32 h-auto mt-2">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Activate Additional Sections -->
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                                    <i class="fas fa-check-square mr-2 text-blue-500"></i>
                                    Active Sections
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Menu Section -->
                                    <div class="space-y-4">
                                        <label for="menu_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-bars mr-2 text-gray-400"></i>Menu
                                        </label>
                                        <input type="checkbox" id="menu_active" name="menu_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['menu_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Contact Section -->
                                    <div class="space-y-4">
                                        <label for="contact_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-phone-alt mr-2 text-gray-400"></i>Contact
                                        </label>
                                        <input type="checkbox" id="contact_active" name="contact_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['contact_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Carousel Section -->
                                    <div class="space-y-4">
                                        <label for="carousel_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-images mr-2 text-gray-400"></i>Carousel
                                        </label>
                                        <input type="checkbox" id="carousel_active" name="carousel_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['carousel_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- About Section -->
                                    <div class="space-y-4">
                                        <label for="about_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-user-circle mr-2 text-gray-400"></i>About
                                        </label>
                                        <input type="checkbox" id="about_active" name="about_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['about_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Services Section -->
                                    <div class="space-y-4">
                                        <label for="services_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-cogs mr-2 text-gray-400"></i>Services
                                        </label>
                                        <input type="checkbox" id="services_active" name="services_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['services_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Features Section -->
                                    <div class="space-y-4">
                                        <label for="features_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-star mr-2 text-gray-400"></i>Features
                                        </label>
                                        <input type="checkbox" id="features_active" name="features_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['features_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Team Section -->
                                    <div class="space-y-4">
                                        <label for="team_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-users mr-2 text-gray-400"></i>Team
                                        </label>
                                        <input type="checkbox" id="team_active" name="team_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['team_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Appointment Section -->
                                    <div class="space-y-4">
                                        <label for="appointment_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>Appointment
                                        </label>
                                        <input type="checkbox" id="appointment_active" name="appointment_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['appointment_active'] ? 'checked' : ''; ?>>
                                    </div>

                                    <!-- Testimonials Section -->
                                    <div class="space-y-4">
                                        <label for="testimonials_active" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-comments mr-2 text-gray-400"></i>Testimonials
                                        </label>
                                        <input type="checkbox" id="testimonials_active" name="testimonials_active" 
                                            class="form-checkbox h-5 w-5 text-blue-600" 
                                            <?php echo $headerData['testimonials_active'] ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-8">
                                <button type="submit" name="save" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600">
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
