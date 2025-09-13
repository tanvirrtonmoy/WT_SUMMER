<?php
session_start();
include("config.php"); // DB connection

// Check if student is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $course = trim($_POST['course']);
    $rating = trim($_POST['rating']);
    $message = trim($_POST['message']);

    // Basic validation
    if (!empty($name) && !empty($course) && !empty($rating) && !empty($message)) {
        
        // Escape values to prevent SQL injection
        $name = $conn->real_escape_string($name);
        $course = $conn->real_escape_string($course);
        $rating = (int)$rating; // rating must be integer
        $message = $conn->real_escape_string($message);
        $email = $conn->real_escape_string($_SESSION['user_email']); // also save who submitted

        // Insert into DB
        $sql = "INSERT INTO student_feedback (student_name, student_email, course, rating, message, created_at)
                VALUES ('$name', '$email', '$course', '$rating', '$message', NOW())";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['feedback_status'] = "success";
        } else {
            $_SESSION['feedback_status'] = "error";
        }

    } else {
        $_SESSION['feedback_status'] = "error";
    }
    
    // Redirect back
    header("Location: student_dashboard.php");
    exit;
}
?>
