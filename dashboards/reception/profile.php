<?php
session_start();
require_once 'db2.php'; // Include the db2.php file to access the connect function

// Establish database connection using the connect() function
$pdo = connect();  // Call the function to get the connection object

// Get the user ID from session
$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    // Fetch user data from the database
    $stmt = $pdo->prepare('SELECT * FROM receptionists WHERE Id_E = :user_id');
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch();

    if (!$user) {
        die("User not found");
    }

    // Get the profile picture from the database or use a default one
    $profilePicture = $user['pfp_pic'] ?? 'reception-one.jpg'; // Set default if no profile picture is set
} else {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Massira Clinic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebarAd.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Mon Profil</h1>

            <div class="flex items-center space-x-6">
                <!-- Profile Picture -->
                <div>
                    <img src="img/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                </div>

                <!-- User Info -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <p class="text-sm text-gray-500">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
                    <p class="text-sm text-gray-500">Nom et Prenom: <?php echo htmlspecialchars($user['Nom'])." ".htmlspecialchars($user['Prenom']) ?></p>

                    <p class="text-sm text-gray-500">Tel: <?php echo htmlspecialchars($user['Tel']); ?></p>
                    <p class="text-sm text-gray-500">Adress: <?php echo htmlspecialchars($user['adresse']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
