<!-- Sidebar -->
<aside id="sidebar" class="sidebar sidebar-fixed bg-white w-64 h-screen shadow-lg z-50">
    <!-- User Profile Section -->
    <div class="flex items-center space-x-4 mb-6 p-4">
        <!-- Profile Picture -->
        <img src="../img/user-profile.jpg" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">

        <!-- Username, Role, and Profile Link -->
        <div>
            <?php
            // Check if 'user' is set in the session to avoid warnings
            if (isset($_SESSION['user'])) {
                echo "<p class='text-lg font-semibold text-gray-700'>".$_SESSION['user']."</p>"; // Display username
            } else {
                echo "<p class='text-lg font-semibold text-gray-700'>Utilisateur</p>"; // Default text if 'user' is not set
            }
            
            // Display the user's role
            if (isset($_SESSION['role'])) {
                echo "<p class='text-sm text-gray-500'>Role: ".$_SESSION['role']."</p>"; // Display role
            }
            ?>
            <a href="profile.php" class="text-sm text-blue-500 hover:text-blue-600">Voir Profil</a>
        </div>
    </div>

    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="#" class="text-lg font-bold text-blue-500 hover:text-blue-600">Mon Dashboard</a>
            <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-2">
            <a href="../includes/acceuil.php" 
               class="nav-link flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                <i class="fas fa-home w-6"></i>
                <span>Accueil</span>
            </a>

            <a href="../users/list_users.php" 
               class="nav-link flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                <i class="fas fa-users w-6"></i>
                <span>Utilisateurs</span>
            </a>

            <!-- Gestion de Contenu Dropdown -->
            <div x-data="{ open: false }" class="nav-link">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                    <span class="flex items-center">
                        <i class="fas fa-file-alt w-6"></i>
                        <span>Gestion de contenu</span>
                    </span>
                    <i class="fas fa-chevron-down" :class="{ 'rotate-180': open }"></i>
                </button>

                <!-- Dropdown Links for Content Pages -->
                <div x-show="open" class="ml-6 mt-2 space-y-1">
                    <a href="../pages/topbar-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Topbar Contenu</a>                 
                    <a href="../pages/header-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Header Contentenu</a>
                    <a href="../pages/about-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Page à propos</a>
                    <a href="../pages/carousel-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Carousel</a>
                    <a href="../pages/contact-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Contacts</a>
                    <a href="../pages/features-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Caractéristiques</a>
                    <a href="../pages/team-content.php" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">Equipe</a>
                </div>
            </div>

            <a href="../pages/change-password.php" 
               class="nav-link flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                <i class="fas fa-lock w-6"></i>
                <span>Changer le mot de passe</span>
            </a>

            <a href="../includes/logout.php" 
               class="flex items-center py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </div>
</aside>

<!-- Alpine.js Script for Dropdown Toggle -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
