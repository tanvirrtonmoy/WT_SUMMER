<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php"; // Database connection

$message = "";

// Handle course deletion
if (isset($_GET['delete'])) {
    $course_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM courses WHERE id = $course_id";
    if (mysqli_query($conn, $delete_query)) {
        // Store message in session so it persists after redirect
        $_SESSION['msg'] = "✅ Course deleted successfully!";
        header("Location: view_courses.php"); 
        exit;
    } else {
        $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
        header("Location: view_courses.php"); 
        exit;
    }
}

// Fetch all courses
$courses_query = "SELECT * FROM courses";
$courses_result = mysqli_query($conn, $courses_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
        }

        header {
            background: #2c3e50;
            color: #fff;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 20px;
        }

        header nav a {
            color: #fff;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            margin: 100px auto;
            padding: 20px;
            max-width: 800px;
        }

        .message {
            padding: 10px;
            background: #2ecc71;
            color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #34495e;
            color: #fff;
        }

        .action-btn {
            padding: 5px 10px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
<header>
    <h1>Manage Courses</h1>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="message" id="msg"><?= $_SESSION['msg']; ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <h2>📘 List of Courses</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                <tr>
                    <td><?= $course['id']; ?></td>
                    <td><?= $course['title']; ?></td>
                    <td>
                        <a href="view_courses.php?delete=<?= $course['id']; ?>" 
                           class="action-btn" 
                           onclick="return confirm('Are you sure you want to delete this course?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
// Auto-hide success message after 3 seconds
setTimeout(() => {
    let msg = document.getElementById("msg");
    if (msg) msg.style.display = "none";
}, 3000);
</script>
</body>
</html>
