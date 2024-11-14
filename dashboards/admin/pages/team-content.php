<?php
// Inclure la connexion à la base de données
include '../includes/dbconnexion.php';

// Fonction pour sécuriser les entrées
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function deleteTeamMember($memberId) {
    global $conn;
    
    // Préparer la requête SQL pour supprimer le membre par son ID
    $query = "DELETE FROM doctors WHERE id = :id";
    $stmt = $conn->prepare($query);
    
    // Lier l'ID du membre
    $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
    
    // Exécuter la requête
    if ($stmt->execute()) {
        header('location:team-content.php'); // Suppression réussie
    } else {
        return false; // Échec de la suppression
    }
}

// Gestion des actions CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['name'], $_POST['department'], $_POST['facebook'], $_POST['twitter'], $_POST['instagram'], $_FILES['photo'])) {
                    $name = sanitize($_POST['name']);
                    $department = sanitize($_POST['department']);
                    $facebook = sanitize($_POST['facebook']);
                    $twitter = sanitize($_POST['twitter']);
                    $instagram = sanitize($_POST['instagram']);
                    
                    // Gestion de l'upload de photo
                    $target_dir = "../../../img/team/";
                    $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = $target_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                        $stmt = $conn->prepare("INSERT INTO doctors (name, department, facebook, twitter, instagram, photo) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $department, $facebook, $twitter, $instagram, $new_filename]);
                    }
                }
                break;

            case 'edit':
                if (isset($_POST['id'], $_POST['name'], $_POST['department'], $_POST['facebook'], $_POST['twitter'], $_POST['instagram'])) {
                    $id = (int)$_POST['id'];
                    $name = sanitize($_POST['name']);
                    $department = sanitize($_POST['department']);
                    $facebook = sanitize($_POST['facebook']);
                    $twitter = sanitize($_POST['twitter']);
                    $instagram = sanitize($_POST['instagram']);
                    
                    if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                        // Nouvelle photo téléchargée
                        $target_dir = "../../../img/team/";
                        $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
                        $new_filename = uniqid() . '.' . $file_extension;
                        $target_file = $target_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                            $stmt = $conn->prepare("UPDATE doctors SET name = ?, department = ?, facebook = ?, twitter = ?, instagram = ?, photo = ? WHERE id = ?");
                            $stmt->execute([$name, $department, $facebook, $twitter, $instagram, $new_filename, $id]);
                        }
                    } else {
                        // Pas de nouvelle photo
                        $stmt = $conn->prepare("UPDATE doctors SET name = ?, department = ?, facebook = ?, twitter = ?, instagram = ? WHERE id = ?");
                        $stmt->execute([$name, $department, $facebook, $twitter, $instagram, $id]);
                    }
                }
                break;

            case 'delete':
                if (isset($_POST['id'])) {
                    $id = (int)$_POST['id'];
                    deleteTeamMember($id);
                }
                break;
        }
    }
}

// Récupération des membres de l'équipe
$stmt = $conn->query("SELECT * FROM doctors ORDER BY id DESC");
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de l'Équipe - Admin Panel</title>
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
            <h1 class="text-2xl font-bold text-gray-700">Gestion de l'Équipe</h1>
            <button onclick="openModal('addTeamModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Ajouter un Membre
            </button>
        </div>

        <!-- Liste des membres -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Photo</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Département</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Facebook</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Twitter</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Instagram</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($team_members as $member): ?>
                    <tr class="border-t border-gray-200">
                        <td class="px-6 py-4">
                            <img src="../../../img/team/<?php echo htmlspecialchars($member['photo']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                 class="w-16 h-16 rounded-full object-cover">
                        </td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['department']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['facebook']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['twitter']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['instagram']); ?></td>
                        <td class="px-6 py-4">
                            <button onclick="openEditModal(<?php echo $member['id']; ?>)" 
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </button>
                            <button onclick="deleteTeamMember(<?php echo $member['id']; ?>)" 
                                    class="text-red-600 hover:text-red-800 ml-3">
                                <i class="fas fa-trash-alt mr-2"></i>Supprimer
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Ajout Membre -->
        <div id="addTeamModal" class="modal hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Ajouter un Membre</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="name" required 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Département</label>
                        <input type="text" name="department" required 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" name="facebook" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Twitter</label>
                        <input type="url" name="twitter" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" name="instagram" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" name="photo" required 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="closeModal('addTeamModal')" 
                                class="text-gray-600 hover:text-gray-800">Annuler</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.js"></script>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function deleteTeamMember(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce membre?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'action';
                hiddenField.value = 'delete';
                form.appendChild(hiddenField);
                var idField = document.createElement('input');
                idField.type = 'hidden';
                idField.name = 'id';
                idField.value = id;
                form.appendChild(idField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
