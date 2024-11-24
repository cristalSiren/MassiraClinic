<!-- Sidebar -->
<?php
// session_start();
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
<aside id="sidebar" class="sidebar sidebar-fixed bg-white w-64 h-screen shadow-lg z-50">
    <!-- User Profile Section -->
    <div class="flex items-center space-x-4 mb-6 p-4">
        <!-- Profile Picture -->
        <!-- <img src="img/reception-one.jpg" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover"> -->

        <img src="img/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">

        <!-- Username and Role -->
        <div>
            <?php
            // Check if session variables are set
            echo "<p class='text-lg font-semibold text-gray-700'>" . (htmlspecialchars($user['Prenom']) ?? 'Utilisateur') . "</p>";
            echo "<p class='text-sm text-gray-500'>Role: " . ($_SESSION['role'] ?? 'receptionnists') . "</p>";
            ?>
            <a href="profile.php" class="text-sm text-blue-500 hover:text-blue-600">Voir Profil</a>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="p-6">
        <nav class="space-y-2">
            <?php if ($_SESSION['role'] === 'receptionists'): ?>
                <a href="profile.php" class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-circle w-6"></i>
                    <span>Mon Profil</span>
                </a>
                <a href="allPatients.php" class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-users w-6"></i>
                    <span>Tous les Patients</span>
                </a>
                <a href="addPatient.php" class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-plus w-6"></i>
                    <span>Créer un Patient</span>
                </a>
            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin-specific links (you can add more admin pages here) -->
                <a href="../admin/includes/allUsers.php" class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard Admin</span>
                </a>
            <?php endif; ?>

            <a href="logout.php" class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </div>
</aside>



<!-- Alpine.js Script for Dropdown Toggle -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
