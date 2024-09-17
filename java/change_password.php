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
            $userData = ['password' => $password];
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    $errors = [];
    if (empty($oldPassword)) $errors['oldPassword'] = "Old password is required.";
    if (empty($newPassword)) $errors['newPassword'] = "New password is required.";
    if ($newPassword !== $confirmNewPassword) $errors['confirmNewPassword'] = "New passwords do not match.";
    if (strlen($newPassword) < 6) $errors['newPassword'] = "New password must be at least 6 characters long.";

    if (empty($errors)) {
        if (!password_verify($oldPassword, $userData['password'])) {
            $errors['oldPassword'] = "Old password is incorrect.";
        } else {
            $updatedUsers = [];
            foreach ($users as $user) {
                list($name, $storedEmail, $password, $contact, $gender) = explode(':', $user);
                if ($storedEmail === $email) {
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatedUsers[] = "$name:$email:$hashedNewPassword:$contact:$gender";
                } else {
                    $updatedUsers[] = $user;
                }
            }
            file_put_contents($usersFile, implode("\n", $updatedUsers));
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Change Password</h2>
            <form name="passwordForm" method="POST" action="change_password.php" onsubmit="return validatePasswordForm()">
                <input type="password" name="oldPassword" placeholder="Old Password">
                <div class="error-message" id="error-oldPassword"><?php echo $errors['oldPassword'] ?? ''; ?></div>
                <input type="password" name="newPassword" placeholder="New Password">
                <div class="error-message" id="error-newPassword"><?php echo $errors['newPassword'] ?? ''; ?></div>
                <input type="password" name="confirmNewPassword" placeholder="Confirm New Password">
                <div class="error-message" id="error-confirmNewPassword"><?php echo $errors['confirmNewPassword'] ?? ''; ?></div>
                <button type="submit" name="changePassword">Change Password</button>
            </form>
            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
