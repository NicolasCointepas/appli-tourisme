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

// Ensure no null values are passed to htmlspecialchars()
$user_email = $user_email ?? '';
$user_name = $user_name ?? '';
$user_company_name = $user_company_name ?? '';
$user_address = $user_address ?? '';
$user_zipcode = $user_zipcode ?? '';
$user_city = $user_city ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/styles.css">
    <title>Update User Information</title>
</head>
<body>
    <div class="container">
        <h2>Update User Information</h2>
        <form method="post" action="update_info_process.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?>" required><br>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($user_company_name, ENT_QUOTES, 'UTF-8'); ?>"><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_address, ENT_QUOTES, 'UTF-8'); ?>"><br>

            <label for="zipcode">Zipcode:</label>
            <input type="text" id="zipcode" name="zipcode" value="<?php echo htmlspecialchars($user_zipcode, ENT_QUOTES, 'UTF-8'); ?>"><br>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user_city, ENT_QUOTES, 'UTF-8'); ?>"><br>

            <button type="submit">Update</button>
        </form>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
