<?php
include 'dashboards/admin/includes/dbconnexion.php';

// Récupérer les informations de la topbar depuis la base de données
$query = "SELECT * FROM topbar_settings LIMIT 1";
$stmt = $conn->query($query);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topbar</title>
    <!-- Lien vers Bootstrap CSS (optionnel si tu ne l'as pas encore inclus dans ton projet) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Topbar: Alignement des éléments */
        .container-fluid {
            padding: 0;
        }

        .row {
            display: flex;
            align-items: center;
        }

        /* Ajuster l'espacement entre les éléments */
        .py-3 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        /* Réseaux sociaux - Espacement et alignement */
        .btn-sm-square {
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Espacement entre les icônes des réseaux sociaux */
        .me-1 {
            margin-right: 8px;
        }

        /* Pour que le texte s'ajuste mieux */
        .small {
            font-size: 14px;
            color: #555;
        }

        /* Styles pour le fond de la topbar */
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-primary {
            color: #007bff !important;
        }
    </style>
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-light p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <!-- Adresse -->
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small><?php echo htmlspecialchars($settings['address']); ?></small>
                </div>
                <!-- Horaires -->
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small><?php echo htmlspecialchars($settings['hours']); ?></small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <!-- Téléphone -->
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small><?php echo htmlspecialchars($settings['phone']); ?></small>
                </div>
                <!-- Réseaux sociaux -->
                <div class="h-100 d-inline-flex align-items-center">
                    <?php if (!empty($settings['facebook'])): ?>
                        <a class="btn btn-sm-square rounded-circle bg-white text-primary me-1" href="<?php echo htmlspecialchars($settings['facebook']); ?>"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['twitter'])): ?>
                        <a class="btn btn-sm-square rounded-circle bg-white text-primary me-1" href="<?php echo htmlspecialchars($settings['twitter']); ?>"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['linkedin'])): ?>
                        <a class="btn btn-sm-square rounded-circle bg-white text-primary me-1" href="<?php echo htmlspecialchars($settings['linkedin']); ?>"><i class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['instagram'])): ?>
                        <a class="btn btn-sm-square rounded-circle bg-white text-primary me-0" href="<?php echo htmlspecialchars($settings['instagram']); ?>"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Optionnel : Lien vers le script Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
