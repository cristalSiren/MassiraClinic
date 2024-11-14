<?php
require_once '../includes/dbconnexion.php';

class UserProfile {
    private $conn;
    private $userData;

    public function __construct($conn) {
        $this->conn = $conn;

        // Vérifier si l'utilisateur est connecté en vérifiant si 'user' est défini dans la session
        if (!isset($_SESSION['username'])) {
            header('Location: logout.php');
            exit;
        }

        // Charger les données utilisateur
        $this->loadUserData();
    }

    private function loadUserData() {
        try {
            // Si vous avez 'user' dans la session, récupérez les données de l'utilisateur
            $username = $_SESSION['user'];  // Utilisez 'user' si 'user_id' n'est pas défini dans la session

            // Préparer la requête pour récupérer les données de l'utilisateur
            $query = "SELECT full_name FROM utilisateurs WHERE user = :username";  // Utilisez 'user' ici
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['username' => $username]);  // Passez 'username' à la requête
            $this->userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$this->userData) {
                throw new Exception("Utilisateur non trouvé.");
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function render() {
        include '../includes/header.php';
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Profil de l'utilisateur</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
        </head>
        <body class="bg-gray-50">
            <div class="flex">
                <?php include '../includes/sidebar.php'; ?>

                <div class="flex-1 p-8">
                    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-4">Profil de l'utilisateur</h1>
                        <div class="text-gray-700">
                            <p><strong>Nom complet :</strong> <?php echo htmlspecialchars($this->userData['full_name']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        include '../includes/footer.php';
    }
}

// Instancier et afficher le profil de l'utilisateur
$userProfile = new UserProfile($conn);
$userProfile->render();
?>
