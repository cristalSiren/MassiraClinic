<?php
// Include the database connection file (this will automatically set the $conn variable)
include '../includes/db2.php';

// Initialize the database connection
$conn = connect(); // This will assign the PDO connection to the $conn variable

// Include the AdminController file for your business logic
require '../includes/AdminController.php';

// Initialize the controller with the PDO connection
$controller = new AdminController($conn);

// Initialize variables
$userType = ''; // Initialize to avoid undefined variable warning
$users = [];
$searchQuery = '';

// Get the user type from the URL query string
if (isset($_GET['user_type'])) {
    $userType = $_GET['user_type'];
}

// Get the search query from the URL query string
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Get users based on the selected type and search query
if ($userType) {
    $users = $controller->getUsersByTypeAndSearch($userType, $searchQuery);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Ensure layout consistency */
        .content-container {
            display: flex;
            height: 100vh; /* Full viewport height */
        }

        .sidebar {
            width: 16rem; /* Fixed width */
            color: white;
            position: sticky; /* Stays fixed while scrolling */
            top: 0;
            height: 100vh; /* Full height */
            overflow-y: auto; /* Scroll if content exceeds */
            /* background-color: #2d3748; */ /* Comment this out or change it */
        }


        .main-content {
            flex: 1; /* Occupy remaining space */
            overflow-y: auto; /* Enable scrolling for main content */
            padding: 1.5rem;
            background-color: #f7fafc;
        }

        .table-container {
            overflow-y: auto; /* Table scrollable */
            max-height: calc(100vh - 150px); /* Adjust table height dynamically */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            position: sticky; /* Keep the header visible while scrolling */
            top: 0;
            background-color: #2d3748; /* Header background */
            color: white;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include_once('sidebarAd.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Tous les Utilisateurs</h2>

            <!-- User Type Selection Form -->
            <form method="GET" class="mb-6">
                <input type="hidden" name="page" value="allUsers">
                <div class="flex items-center">
                    <select name="user_type" class="form-control block w-64 px-3 py-2 border rounded-lg bg-white focus:outline-none" onchange="this.form.submit()">
                        <option value="">Sélectionnez le type d'utilisateur</option>
                        <option value="receptionist" <?php echo ($userType == 'receptionist') ? 'selected' : ''; ?>>Réceptionniste</option>
                        <option value="doctor" <?php echo ($userType == 'doctor') ? 'selected' : ''; ?>>Docteur</option>
                        <option value="nurse" <?php echo ($userType == 'nurse') ? 'selected' : ''; ?>>Infirmière</option>
                        <option value="patient" <?php echo ($userType == 'patient') ? 'selected' : ''; ?>>Patient</option>
                    </select>
                </div>
            </form>

            <!-- Search Bar -->
            <?php if ($userType): ?>
                <form method="GET" class="mb-6 flex items-center">
                    <input type="hidden" name="user_type" value="<?php echo $userType; ?>">
                    <input type="text" name="search" placeholder="Rechercher par nom ou CIN"
                           class="form-control w-1/2 px-3 py-2 border rounded-lg focus:outline-none" 
                           value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg">Rechercher</button>
                </form>
            <?php endif; ?>

            <!-- Display User Table -->
            <?php if ($userType): ?>
                <h4 class="text-2xl font-semibold text-gray-700 mb-4">
                    Utilisateurs - <?php echo ucfirst($userType); ?>
                </h4>
                <div class="table-container bg-white shadow rounded-lg">
                    <table class="table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium">CIN</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Nom</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Téléphone</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucun <?php echo htmlspecialchars($userType); ?> trouvé.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($user['CIN']); ?></td>
                                        <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></td>
                                        <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($user['tel']); ?></td>
                                        <td class="px-4 py-3 text-gray-800">
                                        <a href="edit_user.php?id=<?php echo $user['CIN']; ?>&type=<?php echo $userType; ?>" class="text-blue-500 hover:underline" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                            <span class="mx-2">|</span>
                                            <a href="delete_user.php?id=<?php echo $user['CIN']; ?>&type=<?php echo $userType; ?>" class="text-red-500 hover:underline" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

