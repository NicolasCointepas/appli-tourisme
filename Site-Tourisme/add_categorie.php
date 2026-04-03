<?php
session_start();
include('db.php'); // Include your database connection file


// Function to sanitize input data
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

// Function to create slug from category name
function Slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

// Fetch all categories for parent selection
$sql_categories = "SELECT id, name FROM categories";
$result_categories = $conn->query($sql_categories);

// Insert category into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($conn, $_POST['name']);
    $icon = sanitize($conn, $_POST['icon']);
    $color = sanitize($conn, $_POST['color']);
    $slug = Slug($name); // Generate slug from category name
    $parent = isset($_POST['parent']) ? intval($_POST['parent']) : null; // Handle parent category selection

    // Fetch the current maximum category_order
    $sql_max_order = "SELECT MAX(category_order) AS max_order FROM categories";
    $result = $conn->query($sql_max_order);
    $max_order = $result->fetch_assoc()['max_order'];

    // Determine the next category_order
    $category_order = ($max_order !== null) ? ($max_order + 1) : 1;

    // Prepare and execute the INSERT statement
    $sql = "INSERT INTO categories (name, slug, icon, parent, category_order, color) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $name, $slug, $icon, $parent, $category_order, $color);

    if ($stmt->execute()) {
        header("Location: categorie.php"); // Redirect on successful insertion
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une catégorie</title>
    <link rel="stylesheet" href="styles/styles.css"> <!-- Include your CSS file here -->
</head>
<body>
    <div class="container">
        <div class="navbar">
            <!-- Your navigation links or buttons here -->
        </div>

        <h2>Ajouter une catégorie</h2>
        <form method="post" action="">
            <label for="name">Nom de la catégorie:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <!-- You can replace this input with the appropriate input method for icon selection -->
            <label for="icon">Icon:</label><br>
            <input type="text" id="icon" name="icon" required><br><br>

            <label for="color">Couleur:</label><br>
            <input type="text" id="color" name="color" maxlength="6" pattern="[A-Fa-f0-9]{6}" required><br><br>

            <label for="parent">Parent :</label><br>
            <input type="number" id="parent" name="parent" value="0" required><br><br>

            <button type="submit">Ajouter la catégorie</button>
        </form>

        <div class="button-container">
            <p><button onclick="window.location.href='categorie.php'">Retourner aux catégories</button></p>
            <button onclick="window.location.href='supprimer_categorie.php'">Supprimer une catégorie</button>
        </div>
    </div>
</body>
</html>
