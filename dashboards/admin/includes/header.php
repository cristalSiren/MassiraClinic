<?php

if (!isset($_SESSION['username'])) {
    header('Location: loginAd.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du contenu</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        /* Assurez-vous que le contenu principal défile correctement */
        .main-content {
            height: calc(100vh - 4rem); /* 4rem pour la hauteur du header */
            overflow-y: auto;
        }
        
        /* Styles pour la sidebar mobile */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -256px; /* -16rem, largeur de la sidebar */
                transition: left 0.3s ease-in-out;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Toggle Button - Visible seulement sur mobile -->
    <button id="sidebarToggle" class="fixed top-4 left-4 z-50 lg:hidden bg-white p-2 rounded-md shadow-md hover:bg-gray-100">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Overlay pour mobile -->
    <div id="overlay" class="overlay z-40"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar and main content will go here -->
    </div>
</body>
</html>
