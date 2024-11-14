<?php
require_once '../includes/dbconnexion.php';

try {
    // Base query without filters
    $query = "SELECT * FROM utilisateurs";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Fetch all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database error: " . $e->getMessage());
    $error = "Une erreur est survenue lors de la récupération des données.";
}

// Sanitize and validate input parameters
$searchName = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING) ?? '';
$startDate = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING) ?? '';
$endDate = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING) ?? '';

// Validate dates
if (!empty($startDate) && !validateDate($startDate)) {
    $startDate = '';
}
if (!empty($endDate) && !validateDate($endDate)) {
    $endDate = '';
}

try {
    // Base query with pagination
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    
    // Build the query with proper parameterization
    $params = [];
    $whereConditions = [];

    // Only add conditions if the values are provided
    if (!empty($searchName)) {
        $whereConditions[] = "username LIKE :name";
        $params[':name'] = "%{$searchName}%";
    }
    
    if (!empty($startDate)) {
        $whereConditions[] = "created_at >= :start_date";
        $params[':start_date'] = $startDate;
    }
    
    if (!empty($endDate)) {
        $whereConditions[] = "created_at <= :end_date";
        $params[':end_date'] = $endDate . ' 23:59:59';
    }

    // If no filters, ensure we get all users by setting whereClause to an empty string
    $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
    
    // Count total results for pagination (whether or not filters are applied)
    $countQuery = "SELECT COUNT(*) FROM utilisateurs {$whereClause}";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($params);
    $totalUsers = $countStmt->fetchColumn();
    
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database error: " . $e->getMessage());
    $error = "Une erreur est survenue lors de la récupération des données.";
}

// Helper function to validate date format
function validateDate($date, $format = 'Y-m-d'): bool {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        /* Styles pour le scroll horizontal sur mobile */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Style pour les cartes sur mobile */
        .user-card {
            display: none;
        }

        /* Media queries pour différentes tailles d'écran */
        @media (max-width: 768px) {
            .table-view {
                display: none;
            }
            .user-card {
                display: block;
            }
        }

        /* Animation pour le modal */
        .modal-transition {
            transition: opacity 0.3s ease-in-out;
        }

        /* Styles pour les boutons flottants sur mobile */
        .floating-buttons {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 40;
        }

        @media (min-width: 768px) {
            .floating-buttons {
                position: static;
                flex-direction: row;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold mb-4 md:mb-0">Liste des utilisateurs</h1>
            
            <!-- Boutons d'export responsive -->
            <div class="flex flex-col sm:flex-row gap-2 md:gap-4">
                <a href="export.php?format=csv<?php echo $searchName ? '&name=' . urlencode($searchName) : ''; ?>" 
                   class="btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200 text-center">
                    <i class="fas fa-file-csv mr-2"></i><span class="hidden sm:inline">Export</span> CSV
                </a>
                <a href="export.php?format=pdf<?php echo $searchName ? '&name=' . urlencode($searchName) : ''; ?>" 
                   class="btn bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200 text-center">
                    <i class="fas fa-file-pdf mr-2"></i><span class="hidden sm:inline">Export</span> PDF
                </a>
            </div>
        </div>

        <!-- Message d'erreur responsive -->
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de recherche responsive -->
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
              class="bg-white p-4 md:p-6 rounded-lg shadow-md mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($searchName); ?>" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Nom de l'utilisateur">
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($startDate); ?>" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($endDate); ?>" 
                           class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-search mr-2"></i><span class="hidden sm:inline">Rechercher</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Bouton d'ajout flottant sur mobile -->
        <div class="floating-buttons md:static md:flex md:justify-end mb-4">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full md:rounded-md transition duration-200 shadow-lg md:shadow-none" 
                    id="addUserBtn">
                <i class="fas fa-plus"></i>
                <span class="hidden md:inline ml-2">Ajouter un utilisateur</span>
            </button>
        </div>

        <!-- Vue table pour desktop -->
        <div class="table-view bg-white rounded-lg shadow-md overflow-hidden">
            <div class="table-container">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'ajout</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-3">
                                            <a href="view.php?id=<?php echo $user['id']; ?>" 
                                               class="text-green-500 hover:text-green-700">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit.php?id=<?php echo $user['id']; ?>" 
                                               class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="users/list_users.php?action=delete&id=<?php echo $user['id']; ?>" 
                                               class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 md:px-6 py-4 text-center text-sm">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vue cards pour mobile -->
        <div class="user-card space-y-4">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-medium"><?php echo htmlspecialchars($user['username']); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <span class="text-xs text-gray-500">#<?php echo htmlspecialchars($user['id']); ?></span>
                        </div>
                        <div class="text-sm text-gray-500 mb-3">
                            <i class="far fa-clock mr-1"></i>
                            <?php echo htmlspecialchars($user['created_at']); ?>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <a href="view.php?id=<?php echo $user['id']; ?>" 
                               class="text-green-500 hover:text-green-700 p-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?php echo $user['id']; ?>" 
                               class="text-blue-500 hover:text-blue-700 p-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?php echo $user['id']; ?>" 
                               class="text-red-500 hover:text-red-700 p-2">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
                    Aucun utilisateur trouvé.
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination responsive -->
        <div class="mt-6">
            <ul class="flex flex-wrap justify-center gap-2">
                <?php for ($i = 1; $i <= ceil($totalUsers / $perPage); $i++): ?>
                    <li>
                        <a href="?page=<?php echo $i; ?>" 
                           class="px-3 py-2 text-sm border rounded-md <?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-white text-gray-500 hover:bg-gray-200'; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>

    <!-- Modal responsive -->
    <div id="addUserModal" 
         class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 p-4 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center">
            <div class="bg-white rounded-lg w-full max-w-md md:max-w-xl p-6 relative">
                <button id="closeModal" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
                <div id="modalContent" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        
        document.getElementById('addUserBtn').addEventListener('click', function() {
            // Open the modal
            document.getElementById('addUserModal').classList.remove('hidden');

            // Fetch the content for the modal
            fetch('add_user.php')
            .then(response => response.text())
            .then(data => document.getElementById("modalContent").innerHTML = data)
            .catch(err => console.error("Erreur de chargement du formulaire", err));

        });

                    document.getElementById('closeModal').addEventListener('click', function() {
                        // Close the modal
                        document.getElementById('addUserModal').classList.add('hidden');
                    });
                    document.getElementById('addUserBtn').addEventListener('click', function() {
                // Ouvrir le modal
                document.getElementById('addUserModal').classList.remove('hidden');

                // Charger le contenu du modal
                fetch('users/add_user.php')
                    .then(response => response.text())
                    .then(data => document.getElementById("modalContent").innerHTML = data)
                    .catch(err => console.error("Erreur de chargement du formulaire", err));
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                // Fermer le modal
                document.getElementById('addUserModal').classList.add('hidden');
            });

            // Fermer le modal si l'utilisateur clique en dehors du modal (sur l'arrière-plan)
            document.getElementById('addUserModal').addEventListener('click', function(event) {
                // Vérifier si le clic est sur le fond (et non sur le contenu du modal)
                if (event.target === this) {
                    document.getElementById('addUserModal').classList.add('hidden');
                }
            });


    </script>
</body>
</html>