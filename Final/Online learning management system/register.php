<?php
include("config.php"); // DB connection

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$nameErr = $emailErr = $passErr = $roleErr = "";
$name = $email = $password = $userType = "";
$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) $nameErr = "Name is required";
    else $name = test_input($_POST["name"]);

    if (empty($_POST["email"])) $emailErr = "Email is required";
    else {
        $email = test_input($_POST["email"]);
        if (!preg_match("/^[\w\.-]+@[\w\.-]+\.\w{2,6}$/", $email)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) $passErr = "Password is required";
    else $password = test_input($_POST["password"]);

    if (empty($_POST["userType"])) $roleErr = "Select a role";
    else $userType = test_input($_POST["userType"]);

    if (empty($nameErr) && empty($emailErr) && empty($passErr) && empty($roleErr)) {
        // Insert into DB
        $sql = "INSERT INTO register (username, email, password, role) 
                VALUES ('$name', '$email', '$password', '$userType')";

        if ($conn->query($sql) === TRUE) {
            $successMsg = "✅ Registration successful! You can now <a href='login.php'>login here</a>.";
            $name = $email = $password = $userType = "";
        } else {
            
                $errorMsg = "❌ Error: " . $conn->error;
            }
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {font-family: Arial, sans-serif;background: #f0f8ff;margin: 0;padding: 0;}
        .register_form {width: 350px;margin: 50px auto;padding: 20px;background: #fff;border-radius: 8px;
                        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);text-align: center;}
        .register_form h2 {margin-bottom: 15px;color: #333;}
        .register_form .logo {font-size: 40px;color: #007BFF;margin-bottom: 15px;}
        form div {margin-bottom: 15px;text-align: left;}
        form input[type="text"], form input[type="password"], form select {
            width: 100%;padding: 8px;border: 1px solid #ccc;border-radius: 5px;font-size: 14px;
        }
        form input[type="submit"] {
            width: 100%;background: #007BFF;color: white;border: none;padding: 10px;border-radius: 5px;
            font-size: 15px;cursor: pointer;
        }
        form input[type="submit"]:hover {background: #0056b3;}
        p {margin-top: 10px;font-size: 14px;}
        p a {color: #28a745;text-decoration: none;}
        p a:hover {text-decoration: underline;}
    </style>
</head>
<body>
<div class="register_form">
    <h2>Register</h2>
    <div class="logo">
        <a href="index.php" title="Go to Home"><i class="fa-solid fa-user-plus"></i></a>
    </div>

    <?php if ($successMsg) echo "<p style='color:green;'>$successMsg</p>"; ?>
    <?php if ($errorMsg) echo "<p style='color:red;'>$errorMsg</p>"; ?>

    <form method="post">
        <div>
            Name: <input type="text" name="name" value="<?php echo $name; ?>">
            <span style="color:red"><?php echo $nameErr; ?></span>
        </div><br>
        <div>
            Email: <input type="text" name="email" value="<?php echo $email; ?>">
            <span style="color:red"><?php echo $emailErr; ?></span>
        </div><br>
        <div>
            Password: <input type="password" name="password">
            <span style="color:red"><?php echo $passErr; ?></span>
        </div><br>
        <div>
            Role:
            <select name="userType">
                <option value="">--Select--</option>
                <option value="student" <?php if($userType=="student") echo "selected"; ?>>Student</option>
                <option value="admin" <?php if($userType=="admin") echo "selected"; ?>>Admin</option>
            </select>
            <span style="color:red"><?php echo $roleErr; ?></span>
        </div><br>
        <div>
            <input type="submit" value="Register">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </form>
</div>
</body>
</html>
