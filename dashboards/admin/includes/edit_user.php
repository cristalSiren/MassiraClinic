<?php
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../auth/login.php");
//     exit;
// }
// session_start();
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../loginAd.php");
//     exit;
// }

// include_once  '../../config/config.php';

require '../includes/AdminController.php';
require '../includes/dbconnexion.php'; 
$conn = connect();
$controller = new AdminController($conn);

// Get the user id and type from URL
$userId = isset($_GET['id']) ? $_GET['id'] : '';
$userType = isset($_GET['type']) ? $_GET['type'] : '';

// Fetch the user data from the controller
$user = null;
if ($userId && $userType) {
    $user = $controller->getUserByIdAndType($userId, $userType);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Edit User</h2>

    <?php if ($user): ?>
        <form action="save_user_changes.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userId); ?>">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($userType); ?>">

            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($user['Nom']) ? htmlspecialchars($user['Nom']) : ''; ?>" required>
            </div>

            <!-- Surname Field -->
            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" value="<?php echo isset($user['Prenom']) ? htmlspecialchars($user['Prenom']) : ''; ?>" required>
            </div>

            <!-- Phone Field -->
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($user['Tel']) ? htmlspecialchars($user['Tel']) : ''; ?>" required>
            </div>

            <!-- Address Field -->
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($user['adresse']) ? htmlspecialchars($user['adresse']) : ''; ?>" required>
            </div>

            <!-- Specific Fields for Each User Type -->
            <?php if ($userType == 'receptionist'): ?>
                <div class="form-group">
                    <label for="info_bancaire">Bank Info</label>
                    <input type="text" class="form-control" id="info_bancaire" name="info_bancaire" value="<?php echo isset($user['info_bancaire']) ? htmlspecialchars($user['info_bancaire']) : ''; ?>" required>
                </div>
            <?php elseif ($userType == 'doctor'): ?>
                <div class="form-group">
                    <label for="disponibilite">Availability</label>
                    <input type="text" class="form-control" id="disponibilite" name="disponibilite" value="<?php echo isset($user['disponibilite']) ? htmlspecialchars($user['disponibilite']) : ''; ?>" required>
                </div>
            <?php elseif ($userType == 'nurse'): ?>
                <div class="form-group">
                    <label for="specialite">Specialty</label>
                    <input type="text" class="form-control" id="specialite" name="specialite" value="<?php echo isset($user['specialite']) ? htmlspecialchars($user['specialite']) : ''; ?>" required>
                </div>
            <?php endif; ?> <!-- Close the 'nurse' block -->

            <!-- Patient-Specific Fields -->
            <?php if ($userType == 'patient'): ?>
                <div class="form-group">
                    <label for="date_entree">Date of Entry</label>
                    <input type="date" class="form-control" id="date_entree" name="date_entree" value="<?php echo isset($user['date_entree']) ? htmlspecialchars($user['date_entree']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="historique_medical">Medical History</label>
                    <textarea class="form-control" id="historique_medical" name="historique_medical"><?php echo isset($user['historique_medical']) ? htmlspecialchars($user['historique_medical']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status" value="<?php echo isset($user['status']) ? htmlspecialchars($user['status']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="ordonnance">Prescription</label>
                    <textarea class="form-control" id="ordonnance" name="ordonnance"><?php echo isset($user['ordonnance']) ? htmlspecialchars($user['ordonnance']) : ''; ?></textarea>
                </div>

                <!-- Add Mutuel Field -->
                <div class="form-group">
                    <label for="mutuel">Mutuel Status</label>
                    <select class="form-control" id="mutuel" name="mutuel">
                        <option value="mutuel" <?php echo isset($user['mutuel']) && $user['mutuel'] == 'mutuel' ? 'selected' : ''; ?>>Mutuel</option>
                        <option value="non-mutuel" <?php echo isset($user['mutuel']) && $user['mutuel'] == 'non-mutuel' ? 'selected' : ''; ?>>Non-Mutuel</option>
                    </select>
                </div>
            <?php endif; ?> <!-- Close the 'patient' block -->

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    <?php else: ?>
        <p class="text-danger">User not found. Please check the ID and type.</p>
    <?php endif; ?>
</div>

<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../../assets/js/main.js"></script>

</body>
</html>
