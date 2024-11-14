<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: loginAd.php');
    exit();
}

include '../includes/dbconnexion.php';

class TopbarContent {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getSettings() {
        try {
            $query = "SELECT * FROM topbar_settings LIMIT 1";
            $stmt = $this->db->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des paramètres: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateSettings($data) {
        try {
            $updateData = [];
            
            if (!empty($data['facebook'])) {
                $updateData['facebook'] = filter_var($data['facebook'], FILTER_SANITIZE_URL);
            }
            if (!empty($data['x'])) {
                $updateData['twitter'] = filter_var($data['x'], FILTER_SANITIZE_URL);
            }
            if (!empty($data['linkedin'])) {
                $updateData['linkedin'] = filter_var($data['linkedin'], FILTER_SANITIZE_URL);
            }
            if (!empty($data['instagram'])) {
                $updateData['instagram'] = filter_var($data['instagram'], FILTER_SANITIZE_URL);
            }
            if (!empty($data['address'])) {
                $updateData['address'] = strip_tags($data['address']);
            }
            if (!empty($data['hours'])) {
                $updateData['hours'] = strip_tags($data['hours']);
            }
            if (!empty($data['phone'])) {
                $updateData['phone'] = strip_tags($data['phone']);
            }
            $updateData['is_visible'] = isset($data['is_visible']) ? 1 : 0;
            
            $setClause = [];
            foreach ($updateData as $key => $value) {
                $setClause[] = "$key = :$key";
            }
            $query = "UPDATE topbar_settings SET " . implode(', ', $setClause) . " WHERE id = 1";

            $stmt = $this->db->prepare($query);
            return $stmt->execute($updateData);
        } catch(PDOException $e) {
            error_log("Erreur lors de la mise à jour des paramètres: " . $e->getMessage());
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_topbar'])) {
    $topbar = new TopbarContent($conn);
    if ($topbar->updateSettings($_POST)) {
        $success = "Les paramètres ont été mis à jour avec succès.";
    } else {
        $error = "Une erreur est survenue lors de la mise à jour.";
    }
}

$topbar = new TopbarContent($conn);
$settings = $topbar->getSettings() ?: [
    'address' => '',
    'hours' => '',
    'phone' => '',
    'facebook' => '',
    'twitter' => '',
    'linkedin' => '',
    'instagram' => '',
    'is_visible' => 1
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Topbar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 50;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside class="sidebar bg-gray-800 text-white">
        <?php include '../includes/sidebarAd.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-cog text-blue-600"></i>
                        Paramètres de la Topbar
                    </h1>
                    <p class="mt-2 text-gray-600">Gérez les informations de contact et les liens des réseaux sociaux</p>
                </div>

                <!-- Main Form -->
                <form method="POST" class="space-y-8">
                    <!-- Alert Messages -->
                    <?php if (isset($success)): ?>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700"><?php echo htmlspecialchars($success); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700">
                            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                                <i class="fas fa-address-card"></i>
                                Informations de Contact
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Address Field -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Adresse
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" name="address" 
                                               class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10"
                                               value="<?php echo htmlspecialchars($settings['address']); ?>" 
                                               placeholder="Votre adresse">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-home text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hours Field -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="far fa-clock text-blue-500 mr-2"></i>Horaires
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" name="hours" 
                                               class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10"
                                               value="<?php echo htmlspecialchars($settings['hours']); ?>" 
                                               placeholder="Ex: 09:00 - 18:00">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="far fa-clock text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Field -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-phone text-blue-500 mr-2"></i>Téléphone
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" name="phone" 
                                               class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10"
                                               value="<?php echo htmlspecialchars($settings['phone']); ?>" 
                                               placeholder="Votre numéro de téléphone">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-purple-600 to-purple-700">
                            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                                <i class="fas fa-share-alt"></i>
                                Réseaux Sociaux
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Facebook -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                                </label>
                                <input type="url" name="facebook" 
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="<?php echo htmlspecialchars($settings['facebook']); ?>" 
                                       placeholder="URL de votre page Facebook">
                            </div>

                            <!-- Twitter -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter
                                </label>
                                <input type="url" name="x" 
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="<?php echo htmlspecialchars($settings['twitter']); ?>" 
                                       placeholder="URL de votre page Twitter">
                            </div>

                            <!-- LinkedIn -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn
                                </label>
                                <input type="url" name="linkedin" 
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="<?php echo htmlspecialchars($settings['linkedin']); ?>" 
                                       placeholder="URL de votre profil LinkedIn">
                            </div>

                            <!-- Instagram -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                                </label>
                                <input type="url" name="instagram" 
                                       class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       value="<?php echo htmlspecialchars($settings['instagram']); ?>" 
                                       placeholder="URL de votre compte Instagram">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_visible" value="1" 
                                   <?php echo $settings['is_visible'] ? 'checked' : ''; ?>
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="text-sm font-medium text-gray-700">Activer la topbar</span>
                        </label>

                        <button type="submit" name="update_topbar" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>