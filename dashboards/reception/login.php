<?php
session_start();
require_once 'db2.php'; // Include the db2.php file to access the connect function

$error = ''; // Initialize the $error variable

// Establish database connection using the connect() function
$pdo = connect();  // Call the function to get the connection object

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check user credentials
    $stmt = $pdo->prepare('SELECT * FROM receptionists WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Check if the user exists and verify the password
    if ($user && $password == $user['password']) {
        // Password matches, start session
        $_SESSION['user_id'] = $user['Id_E'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'receptionists';  // Set role to session
        header('Location: allPatients.php'); // Redirect to the dashboard
        exit(); // Make sure to exit after the redirect
    } else {
        echo 'Invalid username or password';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Massira Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-sm bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Connexion</h2>
        
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Nom</label>
                <input type="text" id="username" name="username" 
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Mot de passe</label>
                <input type="password" id="password" name="password" 
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Se connecter</button>
        </form>
    </div>
</body>
</html>
