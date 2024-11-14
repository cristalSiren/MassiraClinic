<?php
// Include the database connection
include 'dashboards/admin/includes/dbconnexion.php';

// Fetch the About page data from the database
$query = "SELECT * FROM about_content WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$aboutData = $stmt->fetch(PDO::FETCH_ASSOC);

// If no data is found for About page, display an error message
if (!$aboutData) {
    die("Aucune donnée trouvée pour la page 'About'.");
}
?>
<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="d-flex flex-column">
                    <!-- Dynamic Images -->
                    <img class="img-fluid rounded w-75 align-self-end" src="img/about/<?php echo htmlspecialchars($aboutData['image1']); ?>" alt="">
                    <img class="img-fluid rounded w-50 bg-white pt-3 pe-3" src="img/about/<?php echo htmlspecialchars($aboutData['image2']); ?>" alt="" style="margin-top: -25%;">
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <!-- Dynamic Title and Subtitle -->
                <p class="d-inline-block border rounded-pill py-1 px-4"><?php echo htmlspecialchars($aboutData['title']); ?></p>
                <h1 class="mb-4"><?php echo htmlspecialchars($aboutData['subtitle']); ?></h1>
                <!-- Dynamic Paragraphs -->
                <p><?php echo nl2br(htmlspecialchars($aboutData['paragraph1'])); ?></p>
                <p class="mb-4"><?php echo nl2br(htmlspecialchars($aboutData['paragraph2'])); ?></p>
                <!-- Dynamic Services -->
                <p><i class="far fa-check-circle text-primary me-3"></i><?php echo htmlspecialchars($aboutData['service1']); ?></p>
                <p><i class="far fa-check-circle text-primary me-3"></i><?php echo htmlspecialchars($aboutData['service2']); ?></p>
                <p><i class="far fa-check-circle text-primary me-3"></i><?php echo htmlspecialchars($aboutData['service3']); ?></p>
                <!-- Dynamic Button Text and Link -->
                <a class="btn btn-primary rounded-pill py-3 px-5 mt-3" href="<?php echo htmlspecialchars($aboutData['button_link']); ?>">
                    <?php echo htmlspecialchars($aboutData['button_text']); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
