<?php
include('db.php');

function sendPasswordRecoveryEmail($email, $token) {
    // Implement email sending logic here
    // Example: mail($email, "Password Recovery", "Click this link to reset your password: http://example.com/reset_password.php?token=$token");
    echo "Password recovery instructions have been sent to your email.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $stmt->bind_result($userId);
        $stmt->fetch();

        // Save the token in the database
        $stmt = $conn->prepare("UPDATE `user` SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
        $stmt->bind_param("si", $token, $userId);
        $stmt->execute();

        // Send password recovery email
        sendPasswordRecoveryEmail($email, $token);
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récupérer le mot de passe</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container login-container">
        <h2>Récupérer le mot de passe</h2>
        <form method="post" action="recover_password.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit">Soumettre</button>
        </form>
        <br>
        <p><a href='login.php'>Retour à la connexion</a></p>
        <a href='register.php'>S'inscrire</a>
    </div>
</body>
</html>
