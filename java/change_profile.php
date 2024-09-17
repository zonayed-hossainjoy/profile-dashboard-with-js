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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $contactNo = $_POST['contactNo'];

    $errors = [];
    if (empty($fullName)) $errors['fullName'] = "Full name is required.";
    if (empty($contactNo) || !preg_match('/^\d{1,14}$/', $contactNo)) $errors['contactNo'] = "Contact number must be up to 14 digits.";

    if (empty($errors)) {
        $updatedUsers = [];
        foreach ($users as $user) {
            list($name, $storedEmail, $password, $contact, $gender) = explode(':', $user);
            if ($storedEmail === $email) {
                $updatedUsers[] = "$fullName:$email:$password:$contactNo:$gender";
            } else {
                $updatedUsers[] = $user;
            }
        }
        file_put_contents($usersFile, implode("\n", $updatedUsers));
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Change Profile</h2>
            <form name="profileForm" method="POST" action="change_profile.php" onsubmit="return validateProfileForm()">
                <input type="text" name="fullName" placeholder="Full Name" value="<?php echo htmlspecialchars($userData['name']); ?>">
                <div class="error-message" id="error-fullName"><?php echo $errors['fullName'] ?? ''; ?></div>
                <input type="text" name="contactNo" placeholder="Contact Number" maxlength="14" value="<?php echo htmlspecialchars($userData['contact']); ?>">
                <div class="error-message" id="error-contactNo"><?php echo $errors['contactNo'] ?? ''; ?></div>
                <button type="submit" name="updateProfile">Update Profile</button>
            </form>
            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
