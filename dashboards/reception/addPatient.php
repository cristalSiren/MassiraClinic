<?php

session_start();

// This must be here!
include 'db2.php';
require 'AdminController.php';

// || $_SESSION['role'] !== 'receptionist'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'receptionists') {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex">

    <!-- Sidebar -->
    <div class="sidebar w-64 bg-gray-800 text-white h-screen fixed top-0 left-0">
        <?php include_once('sidebarAd.php'); ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-8 overflow-auto">
        <div class="container mx-auto mt-5">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Add a New Patient</h2>

            <form action="save_patient.php" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                <div class="form-group mb-4">
                    <label for="name" class="text-gray-700">Nom</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="name" name="name" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="surname" class="text-gray-700">Prenom</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="surname" name="surname" required>
                </div>

                <div class="form-group mb-4">
                    <label for="cin" class="text-gray-700">CIN</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="cin" name="cin" required>
                </div>

                <div class="form-group mb-4">
                    <label for="phone" class="text-gray-700">Phone</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="phone" name="phone" required>
                </div>

                <div class="form-group mb-4">
                    <label for="address" class="text-gray-700">Address</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="address" name="address" required>
                </div>

                <div class="form-group mb-4">
                    <label for="historique_medical" class="text-gray-700">Diagnostic</label>
                    <textarea class="form-control w-full p-3 border border-gray-300 rounded-md" id="historique_medical" name="historique_medical"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="status" class="text-gray-700">Status</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="status" name="status">
                </div>

                <div class="form-group mb-4">
                    <label for="prescription" class="text-gray-700">Prescription</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="prescription" name="prescription">
                </div>

                <div class="form-group mb-4">
                    <label for="mutuel" class="text-gray-700">Mutuel</label>
                    <select class="form-control w-full p-3 border border-gray-300 rounded-md" id="mutuel" name="mutuel">
                        <option value="mutuel">Mutuel</option>
                        <option value="non-mutuel">Non-mutuel</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="date_entree" class="text-gray-700">Date of Entry</label>
                    <input type="date" class="form-control w-full p-3 border border-gray-300 rounded-md" id="date_entree" name="date_entree" required>
                </div>

                <button type="submit" class="btn btn-primary bg-blue-600 text-white p-3 rounded-md">Add Patient</button>
            </form>

        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../../assets/js/main.js"></script>
<script>
    function validateForm() {
        // Basic form validation
        const name = document.getElementById('name').value;
        const cin = document.getElementById('cin').value;

        if (!name.trim() || !cin.trim()) {
            alert("Name and CIN are required.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>
