<?php
session_start();

$fullName = $email = $password = $confirmPassword = $contactNo = $gender = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $contactNo = $_POST['contactNo'] ?? '';
    $gender = $_POST['gender'] ?? '';

    if (empty($fullName)) $errors['fullName'] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email is required.";
    if (empty($password)) $errors['password'] = "Password is required.";
    if ($password !== $confirmPassword) $errors['confirmPassword'] = "Passwords do not match.";
    if (strlen($password) < 6) $errors['password'] = "Password must be at least 6 characters long.";
    if (empty($contactNo) || !preg_match('/^\d{1,14}$/', $contactNo)) $errors['contactNo'] = "Contact number must be up to 14 digits.";
    if (empty($gender)) $errors['gender'] = "Gender is required.";

    // Check if email already exists
    if (file_exists('users.txt')) {
        $users = file('users.txt', FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            list($name, $storedEmail) = explode(':', $user);
            if ($storedEmail === $email) {
                $errors['email'] = "Email is already registered.";
                break;
            }
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userData = "$fullName:$email:$hashedPassword:$contactNo:$gender\n";
        file_put_contents('users.txt', $userData, FILE_APPEND);
        $_SESSION['user'] = $email;
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
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Register</h2>
            <form name="registerForm" method="POST" action="register.php" onsubmit="return validateRegistrationForm()">
                <input type="text" name="fullName" placeholder="Full Name" value="<?php echo htmlspecialchars($fullName); ?>">
                <div class="error-message" id="error-fullName"><?php echo $errors['fullName'] ?? ''; ?></div>

                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                <div class="error-message" id="error-email"><?php echo $errors['email'] ?? ''; ?></div>

                <input type="password" name="password" placeholder="Password">
                <div class="error-message" id="error-password"><?php echo $errors['password'] ?? ''; ?></div>

                <input type="password" name="confirmPassword" placeholder="Confirm Password">
                <div class="error-message" id="error-confirmPassword"><?php echo $errors['confirmPassword'] ?? ''; ?></div>

                <input type="text" name="contactNo" placeholder="Contact Number" maxlength="14" value="<?php echo htmlspecialchars($contactNo); ?>">
                <div class="error-message" id="error-contactNo"><?php echo $errors['contactNo'] ?? ''; ?></div>

                <div class="gender-options">
                <label for="gender">Gender</label>
                    <label><input type="radio" name="gender" value="male" <?php if ($gender === 'male') echo 'checked'; ?>> Male</label>
                    <label><input type="radio" name="gender" value="female" <?php if ($gender === 'female') echo 'checked'; ?>> Female</label>
                </div>
                <div class="error-message" id="error-gender"><?php echo $errors['gender'] ?? ''; ?></div>

                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
