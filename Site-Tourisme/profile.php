<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = '';
$user_email = '';
$user_company_name = '';
$user_address = '';
$user_zipcode = '';
$user_city = '';

// Fetch current user information
$sql = "SELECT email, name, company_name, address, zipcode, city FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_email, $user_name, $user_company_name, $user_address, $user_zipcode, $user_city);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <h2>Profile Information</h2>
        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($user_company_name, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user_address, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Zipcode:</strong> <?php echo htmlspecialchars($user_zipcode, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($user_city, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="profile-actions">
            <button onclick="window.location.href='update_info.php'">Update Information</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
</body>
</html>
