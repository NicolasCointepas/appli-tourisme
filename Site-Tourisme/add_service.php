<?php
session_start();
include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $sql = "INSERT INTO services (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        echo "Service ajouté avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter un service</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
<h1>Ajouter un service</h1>
<form method="post">
    <label for="name">Nom du service:</label>
    <input type="text" id="name" name="name" required><br><br>
    <button type="submit">Ajouter</button>
</form>
<button onclick="window.location.href='services.php'">Retour aux services</button>
</div>
</body>
</html>
