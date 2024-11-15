<?php
// require '../includes/dbconnection.php';
class AdminController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUsersByType($type) {
        $table = '';
        
        // Define the correct table name based on the user type
        if ($type == 'receptionist') {
            $table = 'receptionists';
        } elseif ($type == 'doctor') {
            $table = 'medcin';
        } elseif ($type == 'nurse') {
            $table = 'infirmier';
        } elseif ($type == 'patient') {
            $table = 'patients';
        }

        // If a valid table was found, proceed with the query
        if ($table) {
            $query = "SELECT CIN, nom, prenom, tel FROM $table";
            $stmt = $this->conn->prepare($query);

            try {
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }

        return [];
    }
    
    public function getUserByIdAndType($id, $type) {
        $sql = "";
        if ($type == "receptionist") {
            $sql = "SELECT * FROM receptionists WHERE CIN = :id";
        } elseif ($type == "doctor") {
            $sql = "SELECT * FROM medcin WHERE CIN = :id";
        } elseif ($type == "nurse") {
            $sql = "SELECT * FROM infirmier WHERE CIN = :id";
        } elseif ($type == "patient") {
            $sql = "SELECT * FROM patients WHERE CIN = :id";
        }
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Ensure you're returning the result as an associative array
    }
    public function updateUser($data) {
        // Prepare the SQL query based on user type
        if ($data['user_type'] == 'receptionist') {
            $query = "UPDATE receptionists SET Nom = ?, Prenom = ?, Tel = ?, adresse = ?, info_bancaire = ? WHERE CIN = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['surname']);
            $stmt->bindParam(3, $data['phone']);
            $stmt->bindParam(4, $data['address']);
            $stmt->bindParam(5, $data['info_bancaire']);
            $stmt->bindParam(6, $data['user_id']);
        } elseif ($data['user_type'] == 'doctor') {
            $query = "UPDATE medcin SET Nom = ?, Prenom = ?, Tel = ?, adresse = ?, disponibilite = ? WHERE CIN = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['surname']);
            $stmt->bindParam(3, $data['phone']);
            $stmt->bindParam(4, $data['address']);
            $stmt->bindParam(5, $data['disponibilite']);
            $stmt->bindParam(6, $data['user_id']);
        } elseif ($data['user_type'] == 'nurse') {
            $query = "UPDATE infirmier SET Nom = ?, Prenom = ?, Tel = ?, adresse = ?, specialite = ? WHERE CIN = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['surname']);
            $stmt->bindParam(3, $data['phone']);
            $stmt->bindParam(4, $data['address']);
            $stmt->bindParam(5, $data['specialite']);
            $stmt->bindParam(6, $data['user_id']);
        } elseif ($data['user_type'] == 'patient') {
            $query = "UPDATE patients SET Nom = ?, Prenom = ?, Tel = ?, adresse = ?, date_entree = ?, historique_medical = ?, status = ?, ordonnance = ?, mutuel = ? WHERE CIN = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['name']);
            $stmt->bindParam(2, $data['surname']);
            $stmt->bindParam(3, $data['phone']);
            $stmt->bindParam(4, $data['address']);
            $stmt->bindParam(5, $data['date_entree']);
            $stmt->bindParam(6, $data['historique_medical']);
            $stmt->bindParam(7, $data['status']);
            $stmt->bindParam(8, $data['ordonnance']);
            $stmt->bindParam(9, $data['mutuel']); // Bind the mutuel field for patients
            $stmt->bindParam(10, $data['user_id']);
        } else {
            return false;
        }
    
        // Execute the query and handle errors
        if ($stmt->execute()) {
            return true;
        } else {
            // Log the error for debugging purposes
            $errorInfo = $stmt->errorInfo();
            die('Error executing query: ' . $errorInfo[2]);
        }
    }
    
    public function createUser($data) {
        $userType = $data['user_type'];
        $name = $data['name'];
        $surname = $data['surname'];
        $phone = $data['phone'];
        $address = $data['address'];
        $cin = $data['cin'];
        $date_entree = $data['date_entree']; // Added date_entree
        
        // Prepare the common part of the SQL query (for all user types)
        switch ($userType) {
            case 'receptionist':
                // SQL for inserting a new receptionist
                $sql = "INSERT INTO receptionists (CIN, Nom, Prenom, Tel, Type, adresse, info_bancaire) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $info_bancaire = $data['info_bancaire']; // Specific to receptionists
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cin, $name, $surname, $phone, $userType, $address, $info_bancaire]);
    
            case 'doctor':
                // SQL for inserting a new doctor
                $sql = "INSERT INTO medcin (CIN, Nom, Prenom, Tel, adresse, statut, disponibilite) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $disponibilite = $data['disponibilite']; // Specific to doctors
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cin, $name, $surname, $phone, $address, 'active', $disponibilite]);
    
            case 'nurse':
                // SQL for inserting a new nurse
                $sql = "INSERT INTO infirmier (CIN, Nom, Prenom, Tel, statut, specialite) VALUES (?, ?, ?, ?, ?, ?)";
                $specialite = $data['specialite']; // Specific to nurses
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cin, $name, $surname, $phone, 'active', $specialite]);
    
            case 'patient':
                // SQL for inserting a new patient with date_entree
                $sql = "INSERT INTO patients (CIN, Nom, Prenom, Tel, adresse, historique_medical, status, ordonnance, mutuel, date_entree) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $historique_medical = $data['historique_medical']; // Specific to patients
                $status = $data['status']; // Specific to patients
                $ordonnance = $data['ordonnance']; // Specific to patients
                $mutuel = $data['mutuel']; // Specific to patients
                // Execute the query with the additional date_entree field
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([$cin, $name, $surname, $phone, $address, $historique_medical, $status, $ordonnance, $mutuel, $date_entree]);
    
            default:
                return false; // Invalid user type
        }
    }
    
    
    
    private function getTableByType($type) {
        $tables = [
            'receptionist' => 'receptionists',
            'doctor' => 'medcin',
            'nurse' => 'infirmier',
            'patient' => 'patients'
        ];
        return $tables[$type] ?? null;
    }
 // Delete receptionist by ID
    public function deleteReceptionist($id) {
        $stmt = $this->conn->prepare("DELETE FROM receptionists WHERE CIN = :id");  // Use plural 'receptionists'
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Delete doctor by ID
    public function deleteDoctor($id) {
        $stmt = $this->conn->prepare("DELETE FROM medcin WHERE CIN = :id");  // Use plural 'doctors'
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Delete nurse by ID
    public function deleteNurse($id) {
        $stmt = $this->conn->prepare("DELETE FROM infirmier WHERE CIN = :id");  // Use plural 'nurses'
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Delete patient by CIN
    public function deletePatient($id) {
        $stmt = $this->conn->prepare("DELETE FROM patients WHERE CIN = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    public function getUsersByTypeAndSearch($type, $searchQuery) {
        $table = $this->getTableByType($type);
    
        if ($table) {
            $query = "SELECT CIN, nom, prenom, tel FROM $table WHERE (prenom LIKE :search OR nom LIKE :search OR CIN LIKE :search)";
            $stmt = $this->conn->prepare($query);
            $searchParam = "%$searchQuery%";
            $stmt->bindParam(':search', $searchParam);
    
            try {
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }
    
        return [];
    }
    
}
