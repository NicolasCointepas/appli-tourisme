<?php
session_start(); // Start the session

include('db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Check if place ID is provided
if (!isset($_GET['id'])) {
    die("Error: Place ID not provided.");
}

$place_id = $_GET['id'];

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM places WHERE id = ?");
$stmt->bind_param("i", $place_id);

// Execute the statement
if ($stmt->execute()) {
    echo "Place deleted successfully!";
    // Redirect to a different page if needed
    header("Location: place.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();
?>
