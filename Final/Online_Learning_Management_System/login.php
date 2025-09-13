<?php
session_start();
include("config.php"); // DB connection

$email = $password = "";
$emailErr = $passErr = $loginErr = "";

// When form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) $emailErr = "Email is required";
    else $email = htmlspecialchars(trim($_POST["email"]));

    if (empty($_POST["password"])) $passErr = "Password is required";
    else $password = htmlspecialchars(trim($_POST["password"]));

    if (empty($emailErr) && empty($passErr)) {
        // Check user in DB
        $sql = "SELECT * FROM register WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            $_SESSION['loggedin'] = true;
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
             $_SESSION['user_id'] = $user['id']; // <-- important

            if ($user['role'] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit;
        } else {
            $loginErr = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {font-family: Arial, sans-serif;background: #f0f8ff;margin: 0;padding: 0;}
        .login_form {width: 350px;margin: 50px auto;padding: 20px;background: #fff;border-radius: 8px;
                     box-shadow: 0px 4px 10px rgba(0,0,0,0.1);text-align: center;}
        .login_form h2 {margin-bottom: 15px;color: #333;}
        .login_form .logo {font-size: 40px;color: #28a745;margin-bottom: 15px;}
        form div {margin-bottom: 15px;text-align: left;}
        form input[type="text"], form input[type="password"] {
            width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 5px;font-size: 14px;
        }
        form input[type="submit"] {
            width: 100%;background: #28a745;color: white;border: none;padding: 10px;border-radius: 5px;
            font-size: 15px;cursor: pointer;
        }
        form input[type="submit"]:hover {background: #1e7e34;}
        p {margin-top: 10px;font-size: 14px;}
        p a {color: #007BFF;text-decoration: none;}
        p a:hover {text-decoration: underline;}


          /* Flexbox container for "Remember Me" and "Forgot Password" */
        .remember-me-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }
        .remember-me-container div {
            display: inline;
            margin-right: 5px; /* To add some space between the two */
        }
    </style>
</head>
<body>
<div class="login_form">
    <h2>Login</h2>
    <div class="logo"><a href="index.php" title="Go to Home">
        <i class="fa-solid fa-right-to-bracket"></i>
    </a></div>

    <form method="post">
        <div>
            Email: <input type="text" name="email" value="<?php echo $email; ?>">
            <span style="color:red"><?php echo $emailErr; ?></span>
        </div><br>
        <div>
            Password: <input type="password" name="password">
            <span style="color:red"><?php echo $passErr; ?></span>
        </div><br>
        

         <!-- Remember Me and Forgot Password -->
        <div class="remember-me-container">
            <div>
                <label><input type="checkbox" name="remember_me"> Remember Me</label>
            </div>
            <div>
                <a href="forgot_password.php" style="text-decoration: none; color: #007BFF;">Forgot Password?</a>
            </div>
        </div><br>


        <span style="color:red"><?php echo $loginErr; ?></span><br>
        <div>
            <input type="submit" value="Login">
            <p>Not registered yet? <a href="register.php">Register here</a></p>
        </div>
    </form>
</div>
</body>
</html>
