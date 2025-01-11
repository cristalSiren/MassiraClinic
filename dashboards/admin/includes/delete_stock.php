<?php
// Include the database connection file
include('db2.php'); // Make sure this is correctly included to establish the connection

// Establish the connection
$pdo = connect(); // Call the function to establish the connection

// Check if the connection was successful
if (!$pdo) {
    die("Connection failed: " . $pdo->errorInfo());
}

// Check if the ID parameter is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL query to delete the stock item
    $sql = "DELETE FROM stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind the ID parameter
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Redirect back to the stock page after deletion
        header("Location: stock.php"); // Adjust the page as needed
        exit();
    } else {
        echo "Error deleting the stock item.";
    }
} else {
    echo "No ID specified.";
}
?>
