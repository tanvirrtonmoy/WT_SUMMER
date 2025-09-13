<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Initialize courses array
if (!isset($_SESSION['courses'])) {
    $_SESSION['courses'] = [];
}

// Handle Add Course
if (isset($_POST['add_course'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $id = count($_SESSION['courses']) + 1; // simple auto-increment id

    $_SESSION['courses'][] = [
        'id' => $id,
        'title' => $title,
        'description' => $desc
    ];

    header("Location: add_course.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <style>
        body { font-family: Arial; margin:20px; background:#f4f6f9;}
        input, textarea { width:100%; padding:8px; margin-bottom:10px;}
        button { padding:8px 12px; }
        .success { color: green; }
        a { text-decoration:none; color:#007bff; }
    </style>
</head>
<body>
    <h2>Add New Course</h2>

    <?php if(isset($_GET['success'])) echo "<p class='success'>Course added successfully!</p>"; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Course Title" required>
        <textarea name="description" placeholder="Course Description" required></textarea>
        <button type="submit" name="add_course">Add Course</button>
    </form>

    <p><a href="view_courses.php">View All Courses</a> | <a href="admin_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
