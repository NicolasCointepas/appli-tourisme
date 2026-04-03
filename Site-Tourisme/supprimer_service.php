<?php
session_start();
include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Service supprimé avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supprimer un service</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
<h1>Supprimer un service</h1>
<ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <?php echo htmlspecialchars($row['name']); ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit">Supprimer</button>
            </form>
        </li>
    <?php endwhile; ?>
</ul>
<button onclick="window.location.href='services.php'">Retour aux services</button>
</div>
</body>
</html>
