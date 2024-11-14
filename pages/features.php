<?php
// Inclure la connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php'; // Assurez-vous que le chemin est correct

// Essayer de se connecter à la base de données
try {
    // Créez une instance PDO
    $conn = new PDO("mysql:host=localhost;dbname=clinic-elmassira", "root", ""); // Modifiez selon vos paramètres
    // Définir le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les fonctionnalités depuis la base de données
    $sql = "SELECT title, subtitle, description, icon FROM features_content";
    $result = $conn->query($sql);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher un message d'erreur
    echo "Échec de la connexion : " . $e->getMessage();
    exit();
}
?>

<!-- Début de la section Features -->
<div class="container-fluid bg-primary overflow-hidden my-5 px-lg-0">
    <div class="container feature px-lg-0">
        <div class="row g-0 mx-lg-0">
            <div class="col-lg-6 feature-text py-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="p-lg-5 ps-lg-0">
                    <p class="d-inline-block border rounded-pill text-light py-1 px-4">Fonctionnalités</p>
                    <h1 class="text-white mb-4">Pourquoi nous choisir</h1>
                    <p class="text-white mb-4 pb-2">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo erat amet</p>
                    <div class="row g-4">
                        <?php
                        // Afficher les fonctionnalités depuis la base de données
                        if ($result->rowCount() > 0) {
                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                // Afficher chaque fonctionnalité
                                echo '
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center rounded-circle bg-light" style="width: 55px; height: 55px;">
                                            <i class="fa ' . htmlspecialchars($row['icon']) . ' text-primary"></i>
                                        </div>
                                        <div class="ms-4">
                                            <p class="text-white mb-2">' . htmlspecialchars($row['subtitle']) . '</p>
                                            <h5 class="text-white mb-0">' . htmlspecialchars($row['title']) . '</h5>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo "<p class='text-white'>Aucune fonctionnalité trouvée.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 pe-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                <div class="position-relative h-100">
                    <img class="position-absolute img-fluid w-100 h-100" src="img/feature.jpg" style="object-fit: cover;" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin de la section Features -->
