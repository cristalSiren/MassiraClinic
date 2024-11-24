<?php

session_start();

// This must be here!
include 'db2.php';
require 'AdminController.php';

// || $_SESSION['role'] !== 'receptionist'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'receptionists') {
    header('Location: login.php');
    exit;
}

$conn = connect();
$controller = new AdminController($conn);

// Get search parameters
$searchQuery = $_GET['search'] ?? '';
$entryDate = $_GET['date_entree'] ?? '';

// Fetch patients from the database
$patients = $controller->getPatientsBySearch($searchQuery, $entryDate);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Patients - Massira Clinic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Ensure layout consistency */
        .content-container {
            display: flex;
            height: 100vh; /* Full viewport height */
        }

        /* Sidebar styling */
        .sidebar {
            width: 16rem; /* Fixed width */
            color: white;
            position: sticky; /* Stays fixed while scrolling */
            top: 0;
            height: 100vh; /* Full height */
            overflow-y: auto; /* Scroll if content exceeds */
        }

        /* Main content area */
        .main-content {
            flex: 1; /* Occupy remaining space */
            overflow-y: auto; /* Enable scrolling for main content */
            padding: 1.5rem;
            background-color: #f7fafc;
            height: 100vh; /* Make sure this occupies full viewport height */
        }

        /* Table scrollable container */
        .table-container {
            overflow-y: auto; /* Table scrollable */
            max-height: calc(100vh - 200px); /* Adjust table height dynamically based on page height */
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

        th, td {
            padding: 10px;
            text-align: left;
        }

        td {
            vertical-align: middle;
        }

        /* Make sure the table rows have some space between them */
        tbody tr:hover {
            background-color: #f1f1f1;
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Tous les Patients</h1>

            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap items-center mb-6">
                <input type="text" name="search" placeholder="Rechercher par nom ou CIN"
                    class="form-control w-full md:w-1/3 px-3 py-2 border rounded-lg focus:outline-none mb-4 md:mb-0 md:mr-4"
                    value="<?php echo htmlspecialchars($searchQuery); ?>">
                <input type="date" name="date_entree"
                    class="form-control w-full md:w-1/3 px-3 py-2 border rounded-lg focus:outline-none mb-4 md:mb-0 md:mr-4"
                    value="<?php echo htmlspecialchars($entryDate); ?>">
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg">Rechercher</button>
            </form>

            <!-- Table Container with Scrollable Table -->
            <div class="table-container bg-white shadow rounded-lg">
                <table class="w-full table-auto">
                    <thead class="bg-blue-800 text-white">
                        <tr>
                            <th class="px-4 py-2">CIN</th>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Téléphone</th>
                            <th class="px-4 py-2">Date d'entrée</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">Aucun patient trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $patient): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($patient['CIN']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($patient['Nom'] . ' ' . $patient['Prenom']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($patient['Tel']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($patient['date_entree']); ?></td>
                                    <td class="px-4 py-2 flex space-x-4">
                                        <a href="edit_patient.php?id=<?php echo $patient['Id']; ?>" class="text-blue-500 hover:underline">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_patient.php?id=<?php echo $patient['CIN']; ?>" class="text-red-500 hover:underline"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
