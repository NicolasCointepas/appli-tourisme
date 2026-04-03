<?php
session_start();
include('db.php');

// Function to generate slug
function Slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the category ID is set in the GET parameters
if (!isset($_GET['id'])) {
    die("Category ID not specified.");
}

$category_id = intval($_GET['id']); // Sanitize the input to ensure it's an integer

// Fetch current category information
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

// Check if category is found
if (!$category) {
    die("Category not found.");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $slug = Slug($name);
    $icon = $_POST['icon'];
    $color = $_POST['color'];

    // Update category information
    $sql = "UPDATE categories SET name = ?, slug = ?, icon = ?, color = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $slug, $icon, $color, $category_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the category template page after update
    header("Location: categorie_template.php?id=$category_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier la catégorie</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1>Modifier la catégorie</h1>
    <form method="post" action="modify_categorie.php?id=<?php echo $category_id; ?>">
        <label for="name">Nom de la catégorie:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required><br><br>

        <label for="icon">Icône:</label>
        <input type="text" id="icon" name="icon" value="<?php echo htmlspecialchars($category['icon']); ?>" required><br><br>

        <label for="color">Couleur (hex):</label>
        <input type="text" id="color" name="color" maxlength="6" pattern="[A-Fa-f0-9]{6}" value="<?php echo htmlspecialchars($category['color']); ?>" required><br><br>

        <input type="submit" value="Mettre à jour">
    </form>
    <br>
    <button onclick="window.location.href='categorie_template.php?id=<?php echo $category_id; ?>'">Retour à la catégorie</button>
</div>
</body>
</html>
