<?php
session_start();
include("config.php"); // Your DB connection

// Error messages
$newPassword = $confirmPassword = "";
$newPasswordErr = $confirmPasswordErr = $passwordResetMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $newPassword = htmlspecialchars(trim($_POST["new_password"]));
    $confirmPassword = htmlspecialchars(trim($_POST["confirm_password"]));

    // Validate the new password
    if (empty($newPassword)) {
        $newPasswordErr = "New password is required!";
    } elseif (strlen($newPassword) < 6) {
        $newPasswordErr = "Password should be at least 6 characters!";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $confirmPasswordErr = "Please confirm your password!";
    } elseif ($newPassword !== $confirmPassword) {
        $confirmPasswordErr = "Passwords do not match!";
    }

    // If there are no errors, update the password in the database
    if (empty($newPasswordErr) && empty($confirmPasswordErr)) {
        // Get the email from session
        $email = $_SESSION['email'];

        // Hash the new password for security
        // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the 'register' table
        $sql = "UPDATE register SET password='$newPassword' WHERE email='$email'";

        if (mysqli_query($conn, $sql)) {
            $passwordResetMsg = "Your password has been reset successfully!";
            
            // Unset OTP and email session for security
            unset($_SESSION['otp']);
            unset($_SESSION['email']);

            // Redirect to login page after password reset
            header("Location: login.php");  // Redirect to login page
            exit; // Make sure to stop further execution
        } else {
            $passwordResetMsg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .reset-form { max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; cursor: pointer; border: none; }
        button:hover { background: #218838; }
        span { color: red; }
    </style>
</head>
<body>
    <div class="reset-form">
        <h2>Reset Your Password</h2>

        <?php if ($passwordResetMsg): ?>
            <p style="color: green;"><?= $passwordResetMsg; ?></p>
        <?php endif; ?>

        <form method="post">
            <label>New Password:</label>
            <input type="password" name="new_password" value="<?= $newPassword; ?>" required>
            <span><?= $newPasswordErr; ?></span><br>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" value="<?= $confirmPassword; ?>" required>
            <span><?= $confirmPasswordErr; ?></span><br>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
