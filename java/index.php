<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = [];
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email is required.";
    if (empty($password)) $errors['password'] = "Password is required.";

    if (empty($errors)) {
        $usersFile = 'users.txt';
        if (file_exists($usersFile)) {
            $users = file($usersFile, FILE_IGNORE_NEW_LINES);

            foreach ($users as $user) {
                list($storedName, $storedEmail, $storedPass) = explode(':', $user, 3);
                if ($storedEmail === $email && password_verify($password, $storedPass)) {
                    $_SESSION['user'] = $email;
                    header("Location: dashboard.php");
                    exit;
                }
            }
        }
        $errors['login'] = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form name="loginForm" method="POST" action="index.php" onsubmit="return validateLoginForm()">
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <div class="error-message" id="error-email"><?php echo $errors['email'] ?? ''; ?></div>
                <input type="password" name="password" placeholder="Password">
                <div class="error-message" id="error-password"><?php echo $errors['password'] ?? ''; ?></div>
                <?php if (isset($errors['login'])) { echo "<div class='error-message' id='error-login'>{$errors['login']}</div>"; } ?>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
