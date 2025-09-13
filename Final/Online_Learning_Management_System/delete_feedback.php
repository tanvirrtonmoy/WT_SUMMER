<?php
session_start();
include("config.php");

// ✅ Only allow admin
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM student_feedback WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_msg'] = ['type' => 'green', 'text' => "✅ Feedback deleted successfully!"];
    } else {
        $_SESSION['delete_msg'] = ['type' => 'red', 'text' => "❌ Error deleting feedback: " . $conn->error];
    }
}

header("Location: admin_view_feedback.php");
exit;
