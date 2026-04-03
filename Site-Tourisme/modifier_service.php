<?php
session_start();
include('db.php');

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = "UPDATE services SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    if ($stmt->execute()) {
        echo "Service modifié avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
}

$service = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier un service</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<h1>Modifier un service</h1>
<form method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
    <label for="name">Nom du service:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required><br><br>
    <button type="submit">Modifier</button>
</form>
<button onclick="window.location.href='services.php'">Retour aux services</button>
</body>
</html>
