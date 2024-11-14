<!-- Team Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <p class="d-inline-block border rounded-pill py-1 px-4">Doctors</p>
            <h1>Our Experienced Doctors</h1>
        </div>
        <div class="row g-4">
            <?php
            // Inclure la connexion PDO
            include 'dashboards/admin/includes/dbconnexion.php'; // Assurez-vous que le chemin est correct

            try {
                // Query pour obtenir les informations des médecins
                $query = "SELECT * FROM doctors";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Vérifiez si des médecins sont trouvés
                if (count($doctors) > 0) {
                    foreach ($doctors as $doctor) {
                        $doctorName = $doctor['name'];
                        $department = $doctor['department'];
                        $facebook = $doctor['facebook'];
                        $twitter = $doctor['twitter'];
                        $instagram = $doctor['instagram'];
                        $photo = $doctor['photo']; // Assurez-vous que le champ 'photo' contient le chemin vers l'image
            ?>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item position-relative rounded overflow-hidden">
                            <div class="overflow-hidden">
                                <img class="img-fluid" src="img/team/<?php echo htmlspecialchars($photo); ?>" alt="<?php echo htmlspecialchars($doctorName); ?>">
                            </div>
                            <div class="team-text bg-light text-center p-4">
                                <h5><?php echo htmlspecialchars($doctorName); ?></h5>
                                <p class="text-primary"><?php echo htmlspecialchars($department); ?></p>
                                <div class="team-social text-center">
                                    <?php if (!empty($facebook)): ?>
                                        <a class="btn btn-square" href="<?php echo htmlspecialchars($facebook); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($twitter)): ?>
                                        <a class="btn btn-square" href="<?php echo htmlspecialchars($twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($instagram)): ?>
                                        <a class="btn btn-square" href="<?php echo htmlspecialchars($instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                    }
                } else {
                    echo '<p>No doctors available.</p>';
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>
    </div>
</div>
<!-- Team End -->
