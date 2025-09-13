<?php
session_start();
include("config.php");

$email = $emailErr = $otpErr = "";
$otp = rand(100000, 999999);  // Generate a random OTP

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required!";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));

        // Check if the email exists in the database
        $sql = "SELECT * FROM register WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Send OTP via email (using mail() function or email API)
            // Here we're assuming you have configured email sending.
            // You can use libraries like PHPMailer or an email API to send OTP.
            mail($email, "Your OTP for Password Reset", "Your OTP code is: $otp");

            // Store OTP in session temporarily (for verification)
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            header("Location: verify_otp.php");  // Redirect to OTP verification page
            exit;
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
        body { font-family: Arial, sans-serif; }
        .forgot-form { max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { background: #28a745; color: white; padding: 10px; width: 100%; cursor: pointer; }
        button:hover { background: #218838; }
        span { color: red; }
    </style>
</head>
<body>
    <div class="forgot-form">
        <h2>Forgot Password</h2>
        <form method="post">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $email; ?>">
            <span><?= $emailErr; ?></span><br>
            <button type="submit">Send OTP</button>
        </form>
    </div>
</body>
</html>
s