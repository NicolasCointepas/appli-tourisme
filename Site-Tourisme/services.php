<?php
session_start();
include('db.php');

// Fetch services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Services</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1>Services</h1>
    <ul>
        <?php while ($service = $result->fetch_assoc()): ?>
            <li>
                <a href="service_template.php?id=<?php echo $service['id']; ?>">
                    <?php echo htmlspecialchars($service['name']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Add the Add Service and Delete Service buttons -->
    <button onclick="window.location.href='add_service.php'">Add a Service</button>
    <button onclick="window.location.href='delete_service.php'">Delete a Service</button>
</div>
</body>
</html>
