<?php
include('db.php');

// Function to slugify a string
function Slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

$query = "SELECT id, name FROM categories";
$result = $conn->query($query);

// Check for errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $slug = Slug($name);
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $city = $_POST['city'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $description = $_POST['description'];
    $categories_id = $_POST['categories_id'];
    $users_id = $_SESSION['user_id']; // Replace this with the actual logged-in user id

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO places (name, slug, address, zipcode, city, lat, lng, phone, email, website, description, categories_id, users_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssisssii", $name, $slug, $address, $zipcode, $city, $lat, $lng, $phone, $email, $website, $description, $categories_id, $users_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New place added successfully!";
        // Redirect to a different page if needed
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un lieu</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<div class="container">
    <h1>Ajouter un lieu</h1>
    <form method="POST" action="add_place.php">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address"><br><br>

        <label for="zipcode">Zipcode:</label><br>
        <input type="text" id="zipcode" name="zipcode"><br><br>

        <label for="city">City:</label><br>
        <input type="text" id="city" name="city"><br><br>

        <label for="lat">Latitude:</label><br>
        <input type="text" id="lat" name="lat" required><br><br>

        <label for="lng">Longitude:</label><br>
        <input type="text" id="lng" name="lng" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>

        <label for="website">Website:</label><br>
        <input type="text" id="website" name="website"><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>
        <div>
            <label for="categories_id">Category:</label>
            <select name="categories_id" id="categories_id" required>
                <option value="">Select a Category</option>
                <?php
                // Loop through categories and create options
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </div>

        <input type="submit" value="Add Place">
    </form>
<br>
<a href="place.php"><button>Retourner aux lieux</button></a>
</div>
</body>
</html>