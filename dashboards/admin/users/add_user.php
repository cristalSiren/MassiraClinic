<?php
include '../includes/dbconnexion.php';

// Vérification si le bouton Ajouter a été cliqué
if(isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    // Préparation de la requête d'insertion
    $query = "INSERT INTO utilisateurs (username, password, full_name, email, role, created_at, updated_at)
              VALUES (:username, :password, :full_name, :email, :role, :created_at, :updated_at)";
    $stmt = $conn->prepare($query);

    // Exécution de la requête
    if ($stmt->execute([ 
        ':username' => $username,
        ':password' => $hashed_password,
        ':full_name' => $full_name,
        ':email' => $email,
        ':role' => $role,
        ':created_at' => $created_at,
        ':updated_at' => $updated_at
    ])) {
        // Message de succès
        $success = "Utilisateur ajouté avec succès !";
        // Rediriger automatiquement après succès
        header('Location: ../users/list_users.php');  // Correction du chemin de redirection
        exit();
    } else {
        $error = "Erreur lors de l'ajout de l'utilisateur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-4 md:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Ajouter un utilisateur</h1>

            <!-- Affichage des erreurs ou succès -->
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout d'utilisateur -->
            <form method="POST" action="add_user.php" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="space-y-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <input type="text" id="full_name" name="full_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                        <select id="role" name="role" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin">Admin</option>
                            <option value="user">Utilisateur</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 ease-in-out hover:scale-105">
                        Ajouter l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>