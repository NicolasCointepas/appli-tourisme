<?php
session_start();
include('db.php');

// Check if the category ID is set in the GET parameters
if (!isset($_GET['id'])) {
    die("Category ID not specified.");
}

$category_id = intval($_GET['id']); // Sanitize the input to ensure it's an integer

// Fetch current category information
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$stmt->close();

// Check if category is found
if (!$category) {
    die("Category not found.");
}

// Fetch places in this category
$sql_places = "SELECT * FROM places WHERE categories_id = ?"; // Ensure this matches the actual column name in the `places` table
$stmt_places = $conn->prepare($sql_places);
if (!$stmt_places) {
    die("Prepare failed: " . $conn->error);
}
$stmt_places->bind_param("i", $category_id);
$stmt_places->execute();
$places_result = $stmt_places->get_result();
$stmt_places->close();

echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($category['name']); ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($category['name']); ?></h1>
    <div class="category-details">
        <p><strong>Slug:</strong> <?php echo htmlspecialchars($category['slug']); ?></p>
        <p><strong>Icon:</strong> <img src="<?php echo htmlspecialchars($category['icon']); ?>" alt="Icon"></p>
        <p><strong>Color:</strong> <span style="color: #<?php echo htmlspecialchars($category['color']); ?>;">#<?php echo htmlspecialchars($category['color']); ?></span></p>
        <p><strong>Order:</strong> <?php echo htmlspecialchars($category['category_order']); ?></p>
    </div>

    <h2>Places in this category:</h2>
    <div class="places-list">
        <?php
        // Resetting the pointer and displaying places again
        $places_result->data_seek(0);
        while ($place = $places_result->fetch_assoc()): ?>
            <div class="place-item">
                <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                <p><?php echo htmlspecialchars($place['description']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='categorie.php'">Retour aux catégories</button>
        <button onclick="window.location.href='modify_categorie.php?id=<?php echo $category_id; ?>'">Modifier cette catégorie</button>
    </div>
</div>
</body>
</html>
