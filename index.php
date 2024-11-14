<?php
// Inclure la connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php';
// Récupérer les sections actives depuis la base de données
$query = "SELECT * FROM header_info WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$headerData = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si les données ont été récupérées
if (!$headerData) {
    die("Aucune donnée trouvée pour le header.");
}

// Liste des sections disponibles
$sections = [
    'home',
    'carousel',
    'about',
    'services',
    'features',
    'team',
    'appointement',
    'testimonials',
    'contact'
];

// Tableau des sections activées dans la base de données
$activeSections = [];
foreach ($sections as $section) {
    if (isset($headerData[$section . '_active']) && $headerData[$section . '_active']) {
        $activeSections[] = $section;
    }
}

// Validation de la section (soit elle est active, soit redirigée vers 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
if (!in_array($page, $activeSections)) {
    $page = 'home'; // Si la section n'est pas active, afficher la section 'home'
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('head.php'); ?>

<body>
    <?php
    // Inclure les fichiers de navigation et de header
    include('includes/spinner.php');
    include('includes/topbar.php');
    include('includes/navbar.php');

    // Afficher uniquement les sections actives
    if ($page != 'home') {
        // Si la page n'est pas 'home', inclure seulement si elle est active
        if (in_array($page, $activeSections)) {
            include('pages/' . $page . '.php');
        } else {
            echo "<p>La section demandée n'est pas active.</p>";
        }
    } else {
        // Si la page est 'home', afficher toutes les sections actives
        foreach ($activeSections as $activeSection) {
            include('pages/' . $activeSection . '.php');
        }
    }
    ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- Footer Start -->
    <?php include('includes/footer.php'); ?>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>
</html>
