<?php
session_start();
include("config.php"); // DB connection

// ✅ Only admin can delete
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the message from DB
    $sql = "DELETE FROM student_contact WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_msg'] = [
            'text' => "✅ Message deleted successfully!",
            'type' => "green"
        ];
    } else {
        $_SESSION['delete_msg'] = [
            'text' => "❌ Failed to delete message. Try again!",
            'type' => "red"
        ];
    }
} else {
    $_SESSION['delete_msg'] = [
        'text' => "❌ Invalid message ID!",
        'type' => "red"
    ];
}

// Redirect back to admin view page
header("Location: admin_view_contact.php");
exit;
?>
