<?php
session_start();
include('db.php');

if (!isset($_GET['id'])) {
    echo "ID de lieu non spécifié.";
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM places WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Lieu non trouvé.";
    exit();
}

$place = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Adresse: <?php echo htmlspecialchars($place['address'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Code Postal: <?php echo htmlspecialchars($place['zipcode'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Ville: <?php echo htmlspecialchars($place['city'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Latitude: <?php echo htmlspecialchars($place['lat'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Longitude: <?php echo htmlspecialchars($place['lng'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Téléphone: <?php echo htmlspecialchars($place['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Email: <?php echo htmlspecialchars($place['email'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Site web: <a href="<?php echo htmlspecialchars($place['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($place['website'], ENT_QUOTES, 'UTF-8'); ?></a></p>
    <p>Description: <?php echo htmlspecialchars($place['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    <br>
    <a href="modify_place.php?id=<?php echo $place['id']; ?>">Modifier</a>
    <a href="delete_place.php?id=<?php echo $place['id']; ?>" onclick="return confirm('Are you sure you want to delete this place?');">Delete</a>
    <br>
    <button onclick="window.location.href='place.php'">Retour aux lieux</button>
</div>
</body>
</html>

