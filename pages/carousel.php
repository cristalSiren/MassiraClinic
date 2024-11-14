<?php
// Connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php';

// Récupérer les données du carrousel depuis la base de données
$query = "SELECT * FROM carousel_content";
$stmt = $conn->prepare($query);
$stmt->execute();
$carouselItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Début de l'en-tête -->
<div class="container-fluid header bg-primary p-0 mb-5">
    <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
        <div class="col-lg-6 p-5 wow fadeIn" data-wow-delay="0.1s">
            <h1 class="display-4 text-white mb-5">La Bonne Santé Est La Clé De Tout Bonheur</h1>
            <div class="row g-4">
                <div class="col-sm-4">
                    <div class="border-start border-light ps-4">
                        <h2 class="text-white mb-1" data-toggle="counter-up">123</h2>
                        <p class="text-light mb-0">Médecins Experts</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="border-start border-light ps-4">
                        <h2 class="text-white mb-1" data-toggle="counter-up">1234</h2>
                        <p class="text-light mb-0">Personnel Médical</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="border-start border-light ps-4">
                        <h2 class="text-white mb-1" data-toggle="counter-up">12345</h2>
                        <p class="text-light mb-0">Patients Totals</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
            <div class="owl-carousel header-carousel">
                <?php foreach ($carouselItems as $item): ?>
                    <div class="owl-carousel-item position-relative">
                        <img class="img-fluid" src="img/carousel/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <div class="owl-carousel-text">
                            <h1 class="display-1 text-white mb-0"><?php echo htmlspecialchars($item['title']); ?></h1>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- Fin de l'en-tête -->
