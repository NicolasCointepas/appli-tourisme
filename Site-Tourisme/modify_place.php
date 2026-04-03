<?php
session_start(); // Start the session

include('db.php');

// Function to slugify a string
function Slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
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
    
    // Ensure the user is logged in
    if (isset($_SESSION['user_id'])) {
        $users_id = $_SESSION['user_id'];
    } else {
        die("Error: User not logged in.");
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE places SET name = ?, slug = ?, address = ?, zipcode = ?, city = ?, lat = ?, lng = ?, phone = ?, email = ?, website = ?, description = ?, categories_id = ?, users_id = ? WHERE id = ?");
    $stmt->bind_param("sssssssisssiii", $name, $slug, $address, $zipcode, $city, $lat, $lng, $phone, $email, $website, $description, $categories_id, $users_id, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Place updated successfully!";
        // Redirect to a different page if needed
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch place data if `id` is set in the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $place = $result->fetch_assoc();
    } else {
        die("Error: Place not found.");
    }

    // Close the statement
    $stmt->close();
} else {
    die("Error: No place id provided.");
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify Place</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h2>Modify Place</h2>
    <form action="modify_place.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($place['id']); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($place['name']); ?>" required>
        <br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($place['address']); ?>">
        <br>
        <label for="zipcode">Zipcode:</label>
        <input type="text" id="zipcode" name="zipcode" value="<?php echo htmlspecialchars($place['zipcode']); ?>">
        <br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($place['city']); ?>">
        <br>
        <label for="lat">Latitude:</label>
        <input type="text" id="lat" name="lat" value="<?php echo htmlspecialchars($place['lat']); ?>" required>
        <br>
        <label for="lng">Longitude:</label>
        <input type="text" id="lng" name="lng" value="<?php echo htmlspecialchars($place['lng']); ?>" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($place['phone']); ?>">
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($place['email']); ?>">
        <br>
        <label for="website">Website:</label>
        <input type="text" id="website" name="website" value="<?php echo htmlspecialchars($place['website']); ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($place['description']); ?></textarea>
        <br>
        <label for="categories_id">Category ID:</label>
        <input type="text" id="categories_id" name="categories_id" value="<?php echo htmlspecialchars($place['categories_id']); ?>" required>
        <br>
        <input type="submit" value="Update Place">
    </form>
</div>
</body>
</html>
