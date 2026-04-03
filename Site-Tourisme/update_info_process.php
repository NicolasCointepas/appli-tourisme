<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
    $zipcode = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';

    // Validate the data
    $errors = [];

    if (empty($name)) {
        $errors[] = "Le nom est requis.";
    }

    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    // If there are no errors, update the user's information in the database
    if (empty($errors)) {
        $sql = "UPDATE users SET name = ?, email = ?, company_name = ?, zipcode = ?, address = ?, city = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $email, $company_name, $zipcode, $address, $city, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Les informations ont été mises à jour avec succès.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour des informations.";
            header("Location: update_info.php");
            exit();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: update_info.php");
        exit();
    }
}
?>
