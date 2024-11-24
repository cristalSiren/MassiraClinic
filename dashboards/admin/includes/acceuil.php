<?php
// Include database connection
include '../includes/db2.php';

// Données factices pour la démonstration
$monthlyUsers = [
    ['month' => 'Jan', 'users' => 1200],
    ['month' => 'Fév', 'users' => 1900],
    ['month' => 'Mar', 'users' => 1600],
    ['month' => 'Avr', 'users' => 2100],
    ['month' => 'Mai', 'users' => 2400],
    ['month' => 'Jun', 'users' => 2800]
];

$revenueData = [
    ['month' => 'Jan', 'revenue' => 12000],
    ['month' => 'Fév', 'revenue' => 19000],
    ['month' => 'Mar', 'revenue' => 16000],
    ['month' => 'Avr', 'revenue' => 21000],
    ['month' => 'Mai', 'revenue' => 24000],
    ['month' => 'Jun', 'revenue' => 28000]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <?php 
        include_once('sidebarAd.php');
        ?>

        <!-- Main content area -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tableau de bord</h1>
                <p class="text-gray-600">Bienvenue dans votre espace d'administration</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-users text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Utilisateurs totaux</p>
                            <h3 class="text-2xl font-bold text-gray-700">2,846</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-chart-line text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Visites aujourd'hui</p>
                            <h3 class="text-2xl font-bold text-gray-700">1,257</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Temps moyen</p>
                            <h3 class="text-2xl font-bold text-gray-700">5m 32s</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-envelope text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Messages</p>
                            <h3 class="text-2xl font-bold text-gray-700">28</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Line Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Évolution des utilisateurs</h2>
                    <div class="h-64">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Revenus mensuels</h2>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Configuration commune pour les graphiques
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        padding: 8
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        padding: 8
                    }
                }
            }
        };

        // Users Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthlyUsers, 'month')); ?>,
                datasets: [{
                    label: 'Utilisateurs',
                    data: <?php echo json_encode(array_column($monthlyUsers, 'users')); ?>,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                ...commonOptions,
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($revenueData, 'month')); ?>,
                datasets: [{
                    label: 'Revenus (€)',
                    data: <?php echo json_encode(array_column($revenueData, 'revenue')); ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barThickness: 20,
                    maxBarThickness: 30
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        ...commonOptions.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                return `Revenus: ${context.parsed.y.toLocaleString()} €`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>