<?php
session_start();
include('db.php');

// Fetch all places with their respective category icons
$places_result = $conn->query("
    SELECT places.name, places.lat, places.lng, categories.icon 
    FROM places 
    JOIN categories ON places.categories_id = categories.id
");
$places = [];
while ($row = $places_result->fetch_assoc()) {
    $places[] = $row;
}

$is_admin = false;

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get user roles from the database
function getUserRoles($conn, $user_id) {
    $sql = "SELECT roles FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = $result->fetch_assoc()['roles'];
    return $roles ? json_decode($roles) : [];
}

if (isLoggedIn()) {
    $roles = getUserRoles($conn, $_SESSION['user_id']);
    if ($roles && in_array('admin', $roles)) {
        $is_admin = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <a href="index.php">Home</a>
            <?php if (isLoggedIn()) { ?>
                <a href="update_info.php">Update Info</a>
                <?php if ($is_admin) { ?>
                    <a href="categorie.php">Categories</a>
                    <a href="service.php">Services</a>
                    <a href="place.php">Place</a>
                <?php } ?>
                <a href="logout.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php } ?>
        </div>
        <h1>Bienvenue sur notre site</h1>
        <p>우리의 서비스 및 카테고리를 살펴보십시오!</p>
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script>
        // Initialize the map and set its view to the specified coordinates and zoom level
        var map = L.map('map').setView([51.505, -0.09], 13);

        // Set up the OpenStreetMap layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Function to add markers
        function addMarkers(places) {
            for (var i = 0; i < places.length; i++) {
                var place = places[i];
                var marker = L.marker([place.lat, place.lng]).addTo(map);

                // If there's an icon URL, use it as the icon for the marker
                if (place.icon) {
                    var icon = L.icon({
                        iconUrl: place.icon,
                        iconSize: [32, 32], // size of the icon
                    });
                    marker.setIcon(icon);
                }

                marker.bindPopup("<b>" + place.name + "</b>").openPopup();
            }
        }

        // PHP places data passed to JavaScript
        var places = <?php echo json_encode($places); ?>;

        // Add the markers to the map
        addMarkers(places);
    </script>
</body>
</html>
