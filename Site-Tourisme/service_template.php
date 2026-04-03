<?php
session_start();
include('db.php');

// Fetch the service ID from the URL
$service_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the service details from the database
$sql = "SELECT * FROM services WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    $_SESSION['error'] = "Service non trouvé.";
    header("Location: services.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <?php if(isset($_SESSION['username'])): ?>
                <a href="update_info.php"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php else: ?>
                <a href="login.php">Connectez-vous</a>
            <?php endif; ?>
        </div>
        <div class="content">
            <h1><?php echo htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="service-details">
                <!-- Here you can add more details about the service if available in the database -->
                <p><strong>ID du service:</strong> <?php echo htmlspecialchars($service['id'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Nom du service:</strong> <?php echo htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="buttons">
                <p><button onclick="window.location.href='services.php'">Retour aux services</button></p>
                <a href="modify_service.php?id=<?php echo $service_id; ?>" class="button">Modifier le service</a>
            </div>
        </div>
    </div>
</body>
</html>