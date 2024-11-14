<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Fonction pour sécuriser les entrées
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function deleteService($serviceId) {
    global $conn; 
    
    // Préparer la requête SQL pour supprimer le service par son ID
    $query = "DELETE FROM services WHERE id = :id";
    $stmt = $conn->prepare($query);
    
    // Lier l'ID du service
    $stmt->bindParam(':id', $serviceId, PDO::PARAM_INT);
    
    // Exécuter la requête
    if ($stmt->execute()) {
        header('locatoion:service-content.php'); // Suppression réussie
    } else {
        return false; // Échec de la suppression
    }
}

// Gestion des actions CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['title'], $_POST['icon'], $_POST['description'])) {
                    $title = sanitize($_POST['title']);
                    $icon = sanitize($_POST['icon']);
                    $description = sanitize($_POST['description']);
                    $stmt = $conn->prepare("INSERT INTO services (title, icon, description) VALUES (?, ?, ?)");
                    $stmt->execute([$title, $icon, $description]);
                }
                break;

            case 'edit':
                if (isset($_POST['id'], $_POST['title'], $_POST['icon'], $_POST['description'])) {
                    $id = (int)$_POST['id'];
                    $title = sanitize($_POST['title']);
                    $icon = sanitize($_POST['icon']);
                    $description = sanitize($_POST['description']);
                    $stmt = $conn->prepare("UPDATE services SET title = ?, icon = ?, description = ? WHERE id = ?");
                    $stmt->execute([$title, $icon, $description, $id]);
                }
                break;

            case 'delete':
                if (isset($_POST['id'])) {
                    $id = (int)$_POST['id'];
                    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
                    $stmt->execute([$id]);
                }
                break;
        }
    }
}

// Récupération des services
$stmt = $conn->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include '../includes/sidebarAd.php'; ?>

    <!-- Contenu principal -->
    <div class="flex-1 p-8">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Gestion des Services</h1>
            <button onclick="openModal('addServiceModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Ajouter un Service
            </button>
        </div>

        <!-- Liste des services -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Icône</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr class="border-t border-gray-200">
                        <td class="px-6 py-4">
                            <i class="fa <?php echo htmlspecialchars($service['icon']); ?> text-xl text-gray-600"></i>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($service['title']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($service['description']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openEditModal(<?php echo $service['id']; ?>)" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </button>
                            <button onclick="deleteService(<?php echo $service['id']; ?>)" class="text-red-600 hover:text-red-800 ml-3">
                                <i class="fas fa-trash-alt mr-2"></i>Supprimer
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Ajout Service -->
        <div id="addServiceModal" class="modal hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Ajouter un Service</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Icône</label>
                        <select name="icon" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="fa-heartbeat">Fa-heartbeat</option>
                            <option value="fa-car">Fa-car</option>
                            <option value="fa-cogs">Fa-cogs</option>
                            <option value="fa-bell">Fa-bell</option>
                            <option value="fa-hospital">Fa-hospital</option>
                            <option value="fa-cogs">Fa-cogs</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="4"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('addServiceModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Modification Service -->
        <div id="editServiceModal" class="modal hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Modifier le Service</h2>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editServiceId">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" id="editServiceTitle" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Icône</label>
                        <select name="icon" id="editServiceIcon" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="fa-heartbeat">Fa-heartbeat</option>
                            <option value="fa-car">Fa-car</option>
                            <option value="fa-cogs">Fa-cogs</option>
                            <option value="fa-bell">Fa-bell</option>
                            <option value="fa-hospital">Fa-hospital</option>
                            <option value="fa-cogs">Fa-cogs</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="editServiceDescription" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="4"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editServiceModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fonction d'ouverture du modal
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        // Fonction de fermeture du modal
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Fonction de suppression d'un service
        function deleteService(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                  .then(data => location.reload());
            }
        }

        // Fonction pour ouvrir le modal d'édition avec les données existantes
        function openEditModal(id) {
            // Récupération des données du service via AJAX (ou PHP si nécessaire)
            const formData = new FormData();
            formData.append('action', 'getService');
            formData.append('id', id);

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  document.getElementById('editServiceId').value = data.id;
                  document.getElementById('editServiceTitle').value = data.title;
                  document.getElementById('editServiceIcon').value = data.icon;
                  document.getElementById('editServiceDescription').value = data.description;
                  openModal('editServiceModal');
              });
        }
    </script>

</body>
</html>
