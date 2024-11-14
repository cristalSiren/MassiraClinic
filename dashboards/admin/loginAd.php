<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion à la base de données
include('includes/dbconnexion.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour vérifier les identifiants (en clair)
    $query = "SELECT * FROM utilisateurs WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    
    // Exécuter la requête
    $stmt->execute();

    // Vérifier si un utilisateur est trouvé
    if ($stmt->rowCount() > 0) {
        // Utilisateur trouvé, démarrer la session
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Rediriger vers le tableau de bord et arrêter l'exécution du script
        header('Location: includes/acceuil.php');
        exit();
    } else {
        // Identifiants incorrects
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Clinic Elmassira - Connexion Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded h-100 d-flex align-items-center p-5">
                        <form class="w-100" method="POST" action="loginAd.php">
                            <div class="text-center mb-5">
                                <h1 class="mb-4">Se connecter</h1>
                                <h2 class="mb-4">Admin</h2>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0" name="username" id="username" placeholder="Votre nom d'utilisateur" required>
                                        <label for="username">Nom d'utilisateur</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control border-0" name="password" id="password" placeholder="Votre mot de passe" required>
                                        <label for="password">Mot de passe</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Se connecter</button>
                                </div>
                                <div class="col-12 text-center">
                                    <p><a href="forgot-password.php">Mot de passe oublié ?</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
