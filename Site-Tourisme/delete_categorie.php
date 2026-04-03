<?php
session_start();
include('db.php'); // Include your database connection file


// Function to fetch categories from database
function getCategories($conn) {
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Delete category if requested
if (isset($_POST['delete_category'])) {
    $category_id = $_POST['delete_category'];

    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();

    // Redirect to self after deletion to refresh the list
    header("Location: delete_categorie.php");
    exit();
}

// Fetch categories
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une catégorie</title>
    <link rel="stylesheet" href="styles/styles.css"> <!-- Include your CSS file here -->
</head>
<body>
    <div class="container">
        <div class="navbar">
            <!-- Your navigation links or buttons here -->
        </div>

        <div class="category-container">
            <h2>Supprimer une catégorie</h2>
            <form action="" method="post">
                <?php foreach ($categories as $category): ?>
                    <div class="category-item">
                        <span><?php echo htmlspecialchars($category['name']); ?></span>
                        <button type="submit" name="delete_category" value="<?php echo $category['id']; ?>" class="delete-button">Supprimer</button>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>
        
        <div class="button-container">
        <p><a href="categorie.php" class="button">Retourner aux catégories</a></p>

        </div>
    </div>
</body>
</html>
