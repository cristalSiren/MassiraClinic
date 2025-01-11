<?php

session_start();

// Include necessary files
include 'db2.php';

// Authentication check
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'stock_manager') {
//     header('Location: login.php');
//     exit;
// }

// Database connection
$conn = connect();

// Fetch search parameters
$searchQuery = $_GET['search'] ?? '';
$dateStart = $_GET['date_start'] ?? null;
$dateEnd = $_GET['date_end'] ?? null;

// Fetch stock items from the database
$stockItems = [];
if ($conn) {
    $query = "SELECT * FROM stock WHERE name LIKE :search";

    if ($dateStart && $dateEnd) {
        $query .= " AND added_on BETWEEN :dateStart AND :dateEnd";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', "%$searchQuery%", PDO::PARAM_STR);

    if ($dateStart && $dateEnd) {
        $stmt->bindValue(':dateStart', $dateStart, PDO::PARAM_STR);
        $stmt->bindValue(':dateEnd', $dateEnd, PDO::PARAM_STR);
    }

    $stmt->execute();
    $stockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
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
        .table-container {
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            position: sticky;
            top: 0;
            background-color: #2d3748;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des Stocks</h1>

            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap items-center mb-6" id="searchForm">
                <input type="text" name="search" placeholder="Rechercher par nom"
                    class="form-control w-full md:w-1/3 px-3 py-2 border rounded-lg focus:outline-none mb-4 md:mb-0 md:mr-4"
                    value="<?php echo htmlspecialchars($searchQuery); ?>">
                <input type="date" name="date_start"
                    class="form-control w-full md:w-1/3 px-3 py-2 border rounded-lg focus:outline-none mb-4 md:mb-0 md:mr-4"
                    value="<?php echo htmlspecialchars($dateStart); ?>">
                <input type="date" name="date_end"
                    class="form-control w-full md:w-1/3 px-3 py-2 border rounded-lg focus:outline-none mb-4 md:mb-0 md:mr-4"
                    value="<?php echo htmlspecialchars($dateEnd); ?>">
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg mr-4">Rechercher</button>
                <button type="button" onclick="clearFilters()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Réinitialiser</button>
            </form>

            <!-- Import and Export Buttons -->
            <div class="flex justify-between mb-4">
                <form action="export_stock.php" method="post">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">Exporter vers Excel</button>
                </form>
                <form action="import_stock.php" method="post" enctype="multipart/form-data">
                    <label for="import_file" class="px-4 py-2 bg-yellow-500 text-white rounded-lg cursor-pointer">
                        Importer depuis Excel
                    </label>
                    <input type="file" name="import_file" id="import_file" class="hidden" />
                </form>
            </div>

            <!-- Table Container with Scrollable Table -->
            <div class="table-container bg-white shadow rounded-lg">
                <table class="w-full table-auto">
                    <thead class="bg-blue-800 text-white">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Quantité</th>
                            <th class="px-4 py-2">Prix</th>
                            <th class="px-4 py-2">Date d'expiration</th>
                            <th class="px-4 py-2">Fournisseur</th>
                            <th class="px-4 py-2">Ajouté le</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($stockItems)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-4">Aucun article en stock trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stockItems as $item): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['id']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['price']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['expiration_date']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['supplier']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['added_on']); ?></td>
                                    <td class="px-4 py-2 flex space-x-4">
                                        <a href="edit_stock.php?id=<?php echo $item['id']; ?>" class="text-blue-500 hover:underline">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_stock.php?id=<?php echo $item['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
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

    <script>
        function clearFilters() {
            const form = document.getElementById('searchForm');
            form.reset();
            window.location.href = window.location.pathname;
        }
    </script>
</body>
</html>
