<?php
session_start();
include("config.php"); // DB connection

// Only student can send
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}

$status = "error";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['contact_name'] ?? '');
    $email = trim($_POST['contact_email'] ?? '');
    $subject = trim($_POST['contact_subject'] ?? '');
    $message = trim($_POST['contact_message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // ⚠️ Direct insert (make sure inputs are validated/escaped to avoid SQL injection)
        $sql = "INSERT INTO student_contact (student_name, student_email, subject, message, created_at)
                VALUES ('$name', '$email', '$subject', '$message', NOW())";

        if ($conn->query($sql) === TRUE) {
            $status = "success"; // ✅ Message saved
        } else {
            $status = "error"; // ❌ Insert failed
        }
    }
}

// Store status in session
$_SESSION['contact_status'] = $status;

// Redirect back to dashboard
header("Location: student_dashboard.php#contact-section");
exit;
