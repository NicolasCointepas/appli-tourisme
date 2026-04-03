<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('db.php');

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
        $errors[] = "Veuillez remplir tous les champs obligatoires.";
    } else {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "Inscription réussie!";
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
        }
        ?>
        <form method="post" action="register.php">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">S'inscrire</button>
        </form>
        <br>
        <p><a href='login.php'>Retour à la connexion</a></p>
    </div>
</body>
</html>
