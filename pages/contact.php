<?php
// Inclure la connexion à la base de données
include 'dashboards/admin/includes/dbconnexion.php'; // Assurez-vous que le chemin est correct

// Récupérer les données du contact depuis la base de données
$query = "SELECT * FROM contact_content WHERE id = 1 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$contactData = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si les données sont disponibles
if (!$contactData) {
    echo "Aucune donnée trouvée pour la page de contact.";
    exit;
}
?>

<!-- Début de la section Contact -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Adresse -->
            <div class="col-lg-4">
                <div class="h-100 bg-light rounded d-flex align-items-center p-5">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center rounded-circle bg-white" style="width: 55px; height: 55px;">
                        <i class="fa fa-map-marker-alt text-primary"></i>
                    </div>
                    <div class="ms-4">
                        <p class="mb-2">Adresse</p>
                        <h5 class="mb-0"><?php echo htmlspecialchars($contactData['address']); ?></h5>
                    </div>
                </div>
            </div>
            <!-- Téléphone -->
            <div class="col-lg-4">
                <div class="h-100 bg-light rounded d-flex align-items-center p-5">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center rounded-circle bg-white" style="width: 55px; height: 55px;">
                        <i class="fa fa-phone-alt text-primary"></i>
                    </div>
                    <div class="ms-4">
                        <p class="mb-2">Appelez-nous maintenant</p>
                        <h5 class="mb-0"><?php echo htmlspecialchars($contactData['phone']); ?></h5>
                    </div>
                </div>
            </div>
            <!-- Email -->
            <div class="col-lg-4">
                <div class="h-100 bg-light rounded d-flex align-items-center p-5">
                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center rounded-circle bg-white" style="width: 55px; height: 55px;">
                        <i class="fa fa-envelope-open text-primary"></i>
                    </div>
                    <div class="ms-4">
                        <p class="mb-2">Envoyez-nous un email</p>
                        <h5 class="mb-0"><?php echo htmlspecialchars($contactData['email']); ?></h5>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5">
                    <p class="d-inline-block border rounded-pill py-1 px-4">Contactez-nous</p>
                    <h1 class="mb-4">Vous avez une question ? Veuillez nous contacter !</h1>
                    <p class="mb-4">Le formulaire de contact est actuellement inactif. Obtenez un formulaire de contact fonctionnel et opérationnel avec Ajax & PHP en quelques minutes. Il vous suffit de copier et coller les fichiers, d'ajouter un peu de code et vous êtes prêt. </p>
                    <form action="contact-submit.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom">
                                    <label for="name">Votre nom</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email">
                                    <label for="email">Votre email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Sujet">
                                    <label for="subject">Sujet</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Laissez un message ici" id="message" name="message" style="height: 100px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Envoyer le message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Carte Google Maps -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="h-100" style="min-height: 400px;">
                    <iframe class="rounded w-100 h-100"
                    src="https://www.google.com/maps/embed?pb=<?php echo htmlspecialchars($contactData['map']); ?>"
                    frameborder="0" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin de la section Contact -->
