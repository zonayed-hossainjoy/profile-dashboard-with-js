<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['user'];
$usersFile = 'users.txt';
$userData = [];

if (file_exists($usersFile)) {
    $users = file($usersFile, FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        list($name, $storedEmail, $password, $contact, $gender) = explode(':', $user);
        if ($storedEmail === $email) {
            $userData = ['name' => $name, 'contact' => $contact];
            break;
        }
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <h2>Welcome, <?php echo htmlspecialchars($userData['name']); ?></h2>
            <a href="change_profile.php">Change Profile</a>
            <a href="change_password.php">Change Password</a>
            <form method="POST" action="dashboard.php">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
