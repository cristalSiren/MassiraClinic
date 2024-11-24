<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
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
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Add a New User</h2>

            <form action="save_user_create.php" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                <div class="form-group mb-4">
                    <label for="name" class="text-gray-700">Name</label>
                    <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="name" name="name" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="surname" class="text-gray-700">Surname</label>
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
                    <label for="type" class="text-gray-700">User Type</label>
                    <select class="form-control w-full p-3 border border-gray-300 rounded-md" id="type" name="type" required onchange="showAdditionalFields()">
                        <option value="" disabled selected>Select a user type</option>
                        <option value="receptionist">Receptionist</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>

                <!-- Additional fields for each user type -->
                <div id="receptionistFields" class="user-specific-fields" style="display: none;">
                    <div class="form-group mb-4">
                        <label for="info_bancaire" class="text-gray-700">Bank Information</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="info_bancaire" name="info_bancaire">
                    </div>
                    <!-- Profile Picture input -->
                    <div class="form-group mb-4">
                        <label for="profile_pic" class="text-gray-700">Profile Picture</label>
                        <input type="file" class="form-control w-full p-3 border border-gray-300 rounded-md" id="profile_pic" name="profile_pic" accept="image/*">
                    </div>
                    <!-- Username input -->
                    <div class="form-group mb-4">
                        <label for="username" class="text-gray-700">Username</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="username" name="username" required>
                    </div>
                    <!-- Password input -->
                    <div class="form-group mb-4">
                        <label for="password" class="text-gray-700">Password</label>
                        <input type="password" class="form-control w-full p-3 border border-gray-300 rounded-md" id="password" name="password" required>
                    </div>
                </div>

                <div id="doctorFields" class="user-specific-fields" style="display: none;">
                    <div class="form-group mb-4">
                        <label for="disponibilite" class="text-gray-700">Availability</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="disponibilite" name="disponibilite">
                    </div>
                </div>

                <div id="nurseFields" class="user-specific-fields" style="display: none;">
                    <div class="form-group mb-4">
                        <label for="specialite" class="text-gray-700">Specialty</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="specialite" name="specialite">
                    </div>
                </div>

                <div id="patientFields" class="user-specific-fields" style="display: none;">
                    <div class="form-group mb-4">
                        <label for="historique_medical" class="text-gray-700">Medical History</label>
                        <textarea class="form-control w-full p-3 border border-gray-300 rounded-md" id="historique_medical" name="historique_medical"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label for="status" class="text-gray-700">Status</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="status" name="status">
                    </div>
                    <div class="form-group mb-4">
                        <label for="ordonnance" class="text-gray-700">Prescription</label>
                        <input type="text" class="form-control w-full p-3 border border-gray-300 rounded-md" id="ordonnance" name="ordonnance">
                    </div>
                    <div class="form-group mb-4">
                        <label for="mutuel" class="text-gray-700">Mutuel</label>
                        <select class="form-control w-full p-3 border border-gray-300 rounded-md" id="mutuel" name="mutuel">
                            <option value="mutuel">Mutuel</option>
                            <option value="non-mutuel">Non-mutuel</option>
                        </select>
                    </div>
                </div>

                <!-- Added Date of Entry for All Users -->
                <div class="form-group mb-4">
                    <label for="date_entree" class="text-gray-700">Date of Entry</label>
                    <input type="date" class="form-control w-full p-3 border border-gray-300 rounded-md" id="date_entree" name="date_entree" required>
                </div>

                <button type="submit" class="btn btn-primary bg-blue-600 text-white p-3 rounded-md">Create User</button>
            </form>

        </div>
    </div>
</div>

<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../../assets/js/main.js"></script>
<script>
    function showAdditionalFields() {
        var userType = document.getElementById('type').value;

        // Hide all fields initially
        var allFields = document.querySelectorAll('.user-specific-fields');
        allFields.forEach(function(field) {
            field.style.display = 'none';
        });

        // Show the fields for the selected user type
        if (userType == 'receptionist') {
            document.getElementById('receptionistFields').style.display = 'block';
        } else if (userType == 'doctor') {
            document.getElementById('doctorFields').style.display = 'block';
        } else if (userType == 'nurse') {
            document.getElementById('nurseFields').style.display = 'block';
        } else if (userType == 'patient') {
            document.getElementById('patientFields').style.display = 'block';
        }
    }

    function validateForm() {
        var userType = document.getElementById('type').value;
        if (userType === "") {
            alert("Please select a valid user type.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>

</body>
</html>
