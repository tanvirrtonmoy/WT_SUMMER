<?php
session_start();
include("config.php");

$email = $emailErr = "";
$otp = rand(100000, 999999);  // Generate random OTP

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required!";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));

        // Check if the email exists in DB
        $sql = "SELECT * FROM register WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Store OTP in session for verification
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Show OTP on the page (for demo purposes)
            $otpMsg = "Your OTP code is: $otp";
        } else {
            $emailErr = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        .forgot-form { max-width:400px; margin:50px auto; padding:20px; background:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        input { width:100%; padding:10px; margin:10px 0;}
        button { width:100%; padding:10px; background:#28a745; color:#fff; cursor:pointer; border:none;}
        button:hover { background:#218838; }
        span { color:red; }
    </style>
</head>
<body>
    <div class="forgot-form">
        <h2>Forgot Password</h2>
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $email; ?>" required>
            <span><?= $emailErr; ?></span><br>
            <button type="submit">Generate OTP</button>
        </form>

        <?php if (isset($otpMsg)): ?>
            <p><?= $otpMsg; ?></p>
            <p><a href="verify_otp.php">Click here to verify OTP</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
