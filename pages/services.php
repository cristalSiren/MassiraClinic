<?php
// Inclure la connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php'; // Assurez-vous que le chemin est correct

// Requête pour récupérer les services depuis la table dbo.services
$query = "SELECT * FROM services";
$stmt = $conn->query($query);

// Vérifier si la requête a retourné des résultats
if ($stmt) {
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <p class="d-inline-block border rounded-pill py-1 px-4">Services</p>
            <h1>Health Care Solutions</h1>
        </div>
        <div class="row g-4">
            <?php
            // Si des services existent, les afficher
            if (!empty($services)) {
                foreach ($services as $service) {
                    echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="service-item bg-light rounded h-100 p-5">
                                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-4" style="width: 65px; height: 65px;">
                                    <i class="fa ' . htmlspecialchars($service['icon']) . ' text-primary fs-4"></i>
                                </div>
                                <h4 class="mb-3">' . htmlspecialchars($service['title']) . '</h4>
                                <p class="mb-4">' . htmlspecialchars($service['description']) . '</p>
                                <a class="btn" href=""><i class="fa fa-plus text-primary me-3"></i>Read More</a>
                            </div>
                        </div>';
                }
            } else {
                echo '<p>No services available at the moment.</p>';
            }
            ?>
        </div>
    </div>
</div>
<!-- Service End -->

<?php
// Fermer la connexion à la base de données
$conn = null;
?>
