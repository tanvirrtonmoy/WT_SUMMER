<?php
session_start();
include("config.php"); // your DB connection

// Error messages
$otpErr = $successMsg = "";

// Check if OTP session exists
if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header("Location: forgot_password.php"); // Redirect to forgot password if OTP is not set
    exit;
}

// Handle OTP form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = htmlspecialchars(trim($_POST["otp"])); // OTP entered by user

    // Verify the entered OTP with the session OTP
    if ($enteredOtp == $_SESSION['otp']) {
        // OTP is correct, redirect to reset password page
        header("Location: reset_password.php"); // Go to password reset page
        exit;
    } else {
        // OTP is incorrect, show error message
        $otpErr = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        .otp-form { max-width:400px; margin:50px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        input { width:100%; padding:10px; margin:10px 0;}
        button { width:100%; padding:10px; background:#28a745; color:#fff; cursor:pointer; border:none;}
        button:hover { background:#218838; }
        span { color:red; }
    </style>
</head>
<body>
    <div class="otp-form">
        <h2>Verify OTP</h2>

        <form method="post">
            <label>Enter OTP:</label>
            <input type="text" name="otp" required>

            <span><?= $otpErr; ?></span><br>
            <button type="submit">Verify OTP</button>
        </form>

        <p>
            OTP sent to your email: <strong><?= $_SESSION['email']; ?></strong>
        </p>

        <?php if ($otpErr): ?>
            <p style="color: red;">Incorrect OTP. Please try again.</p>
        <?php endif; ?>
    </div>
</body>
</html>
