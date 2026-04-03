<?php
session_start();
include('db.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch the hashed password and user info
    $sql = "SELECT id, password, roles, name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $roles, $username);
    $stmt->fetch();

    // Check if a user with the provided email exists
    if ($stmt->num_rows == 1) {
        // Verify the provided password against the hashed password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set up the session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['roles'] = $roles;

            // Redirect to the index page
            header("Location: index.php");
            exit();
        } else {
            // Incorrect password
            $login_error = "Invalid email or password.";
        }
    } else {
        // No user found with the provided email
        $login_error = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container login-container">
        <h1>Connexion</h1>
        <?php if (isset($login_error)): ?>
            <p><?php echo $login_error; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" required><br>
            <button type="submit">Login</button>
            <br>
            <p><a href='register.php'>Vous n'avez pas de compte? Inscrivez-vous!</a></p>
            <a href='recover_password.php'>Mot de passe oublié?</a>
        </form>
    </div>
</body>
</html>



