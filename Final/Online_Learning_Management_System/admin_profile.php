<?php
session_start();
include("config.php"); // DB connection

// Only allow admin
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM register WHERE email='$user_email' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    die("User not found in database!");
}

$success_msg = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['email'];
    $password = $_POST['password'];
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : $password;

    // Handle image upload
    $profile_image = $user['profile_image'] ?? "assets/default.png";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $filename = uniqid() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_path = $upload_dir . $filename;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_path);
        $profile_image = $target_path;
    }

    // Update in DB
    $update_sql = "UPDATE register SET email='$new_email', password='$new_password', profile_image='$profile_image' WHERE id={$user['id']}";
    if ($conn->query($update_sql) === TRUE) {
        $success_msg = "Profile updated successfully!";

        // Update session values
        $_SESSION['user_email'] = $new_email;
        $_SESSION['user_password'] = $new_password;
        $_SESSION['user_image'] = $profile_image;

        // Reload updated user
        $user['email'] = $new_email;
        $user['password'] = $new_password;
        $user['profile_image'] = $profile_image;
    } else {
        $success_msg = "Error updating profile: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile - EduLearn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh;}
        .profile-card {background: #fff; width: 380px; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
        .profile-card img {width: 120px; height: 120px; border-radius: 50%; border: 3px solid #e74c3c; margin-bottom: 15px; cursor: pointer;}
        .profile-card h2 {margin: 10px 0 5px; font-size: 22px; color: #333;}
        input[type=email], input[type=password] {width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px;}
        .btn {background: #e74c3c; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; margin-top: 10px; cursor: pointer;}
        .btn:hover {background: #c0392b;}
        .success {color: green; margin-bottom: 10px;}


    </style>
</head>
<body>
    <div class="profile-card">
        <?php if($success_msg) echo "<p class='success'>$success_msg</p>"; ?>

        <!-- Profile Image -->
        <form method="POST" enctype="multipart/form-data">
           <div class="profile-image-wrapper" onclick="document.getElementById('profileInput').click();">
    <?php if (!empty($user['profile_image'])): ?>
        <img src="<?php echo $user['profile_image']; ?>" id="profilePreview" alt="Profile Image">
    <?php else: ?>
        <div class="profile-placeholder" id="profilePreview">Profile Image</div>
    <?php endif; ?>
</div>
<input type="file" name="profile_image" id="profileInput" style="display:none;" accept="image/*" onchange="previewImage(event)">

            <!-- Display Info -->
            <h2><?php echo htmlspecialchars($_SESSION['user_name']); ?> </h2><br>
            <h2 id="emailDisplay"><?php echo htmlspecialchars($user['email']); ?></h2><br>
            <h2 id="passwordDisplay"><?php echo htmlspecialchars($user['password']); ?></h2><br>

            <!-- Manage Profile Button -->
            <button type="button" class="btn" onclick="toggleEdit()">Manage Profile</button>
            <a href="admin_dashboard.php" class="btn btn-back">Back</a>

            <!-- Edit Form -->
            <div id="editForm" style="display:none; margin-top:15px;">
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                <input type="password" name="password" value="<?php echo $user['password']; ?>">
                <input type="password" name="new_password" placeholder="New Password (optional)">
                <button type="submit" class="btn"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('profilePreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
function toggleEdit() {
    let form = document.getElementById('editForm');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
}
</script>
</body>
</html>
