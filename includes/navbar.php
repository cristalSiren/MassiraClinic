<?php
// Activer le rapport d'erreurs pour faciliter le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir une classe pour gérer les données du header
class HeaderManager {
    private $conn;
    private $headerData;
    
    // Configuration par défaut
    private $defaultConfig = [
        'about_active' => 0,
        'features_active' => 0,
        'services_active' => 0,
        'team_active' => 0,
        'appointment_active' => 0,
        'testimonials_active' => 0,
        'contact_active' => 0,
        'logo' => '' // Ajout de l'URL du logo
    ];

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        $this->loadHeaderData();
    }

    private function loadHeaderData() {
        try {
            $query = "SELECT * FROM header_info WHERE id = 1 LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->headerData = $result ?: $this->defaultConfig;
        } catch (PDOException $e) {
            error_log("Erreur de base de données: " . $e->getMessage());
            $this->headerData = $this->defaultConfig;
        }
    }

    public function isActive($section) {
        return isset($this->headerData[$section . '_active']) && $this->headerData[$section . '_active'];
    }

    public function getData() {
        return $this->headerData;
    }

    public function getLogoUrl() {
        return "dashboards/admin/img/logo/".$this->headerData['logo'] ?: 'dashboards/admin/img/logo/E.png'; // Chemin par défaut si logo non trouvé
    }
}

// Inclusion de la connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php';

// Initialisation du gestionnaire de header
$headerManager = new HeaderManager($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Clinique Elmassira</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Clinique médicale à Elmassira offrant des services de santé complets" name="description">
    <meta content="clinique, santé, médical, Elmassira, docteur, soins" name="keywords">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0 wow fadeIn" data-wow-delay="0.1s">
    <a href="index.php?page=home" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <?php if ($headerManager->getLogoUrl()): ?>
            <img src="<?php echo $headerManager->getLogoUrl(); ?>" alt="Logo" style="height: 8rem; margin-right: 10px;">
        <?php else: ?>
            <h1 class="m-0 text-primary"><i class="far fa-hospital me-3"></i>Clinique Elmassira</h1>
        <?php endif; ?>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <?php
            // Fonction pour générer la classe active
            function isCurrentPage($pageName) {
                return isset($_GET['page']) && $_GET['page'] === $pageName ? 'active' : '';
            }
            ?>
            
            <a href="index.php?page=home" class="nav-item nav-link <?php echo isCurrentPage('home'); ?>">Accueil</a>

            <?php if ($headerManager->isActive('about')): ?>
                <a href="index.php?page=about" class="nav-item nav-link <?php echo isCurrentPage('about'); ?>">À propos</a>
            <?php endif; ?>

            <?php if ($headerManager->isActive('services')): ?>
                <a href="index.php?page=services" class="nav-item nav-link <?php echo isCurrentPage('services'); ?>">Services</a>
            <?php endif; ?>

            <?php
            $hasDropdownItems = $headerManager->isActive('features') || 
                               $headerManager->isActive('team') || 
                               $headerManager->isActive('appointment') || 
                               $headerManager->isActive('testimonials');
            
            if ($hasDropdownItems):
            ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu rounded-0 rounded-bottom m-0">
                        <?php if ($headerManager->isActive('features')): ?>
                            <a href="index.php?page=features" class="dropdown-item">Caractéristiques</a>
                        <?php endif; ?>
                        
                        <?php if ($headerManager->isActive('team')): ?>
                            <a href="index.php?page=team" class="dropdown-item">Nos Médecins</a>
                        <?php endif; ?>

                        <?php if ($headerManager->isActive('appointment')): ?>
                            <a href="index.php?page=appointement" class="dropdown-item">Rendez-vous</a>
                        <?php endif; ?>

                        <?php if ($headerManager->isActive('testimonials')): ?>
                            <a href="index.php?page=testimonials" class="dropdown-item">Témoignages</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($headerManager->isActive('contact')): ?>
                <a href="index.php?page=contact" class="nav-item nav-link <?php echo isCurrentPage('contact'); ?>">Contact</a>
            <?php endif; ?>
        </div>

        <?php if ($headerManager->isActive('appointment')): ?>
            <a href="index.php?page=appointement" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">
                Rendez-vous<i class="fa fa-arrow-right ms-3"></i>
            </a>
        <?php endif; ?>
    </div>
</nav>
<!-- Navbar End -->

</body>
</html>