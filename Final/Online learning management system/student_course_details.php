<?php
session_start();

// Redirect if not logged in as student
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}

// Courses Data (could be replaced with DB later)
$courses = [
    1 => [
        "title" => "Web Development",
        "description" => "Learn HTML, CSS, JavaScript and build modern websites.",
        "image" => "image/webdev.png",
        "duration" => "8 Weeks",
        "instructor" => "John Doe"
    ],
    2 => [
        "title" => "Database Systems",
        "description" => "Master SQL and database design for scalable applications.",
        "image" => "image/database.jpg",
        "duration" => "6 Weeks",
        "instructor" => "Jane Smith"
    ],
    3 => [
        "title" => "Embedded Systems",
        "description" => "Explore microcontrollers and real-time embedded applications.",
        "image" => "image/embedded.png",
        "duration" => "10 Weeks",
        "instructor" => "Michael Lee"
    ],
    4 => [
        "title" => "Computer Networks",
        "description" => "Understand networking protocols and secure communication.",
        "image" => "image/networks.jpg",
        "duration" => "7 Weeks",
        "instructor" => "Alice Brown"
    ],
    5 => [
        "title" => "Artificial Intelligence",
        "description" => "Dive into AI, machine learning and intelligent systems.",
        "image" => "image/ai.jpeg",
        "duration" => "12 Weeks",
        "instructor" => "David Wilson"
    ],
    6 => [
        "title" => "Cyber Security",
        "description" => "Learn how to protect systems from modern cyber threats.",
        "image" => "image/cybersecurity.jpeg",
        "duration" => "9 Weeks",
        "instructor" => "Emily Davis"
    ],
];

// Get Course ID from URL
$course_id = $_GET['id'] ?? null;

if (!$course_id || !isset($courses[$course_id])) {
    echo "<h2>Course not found!</h2>";
    exit;
}

$course = $courses[$course_id];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $course['title']; ?> - Course Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0;}
        .course-details {max-width: 700px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);}
        .course-details img {width: 100%; border-radius: 10px;}
        .course-details h2 {color: #333; margin-top: 20px;}
        .course-details p {line-height: 1.6; color: #555;}
        .course-meta {margin-top: 15px;}
        .course-meta strong {color: #333;}
        .btn-back {display: inline-block; margin-top: 20px; padding: 10px 15px; background: #3498db; color: #fff; text-decoration: none; border-radius: 5px;}
        .btn-back:hover {background: #2980b9;}
    </style>
</head>
<body>
    <div class="course-details">
        <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>">
        <h2><?php echo $course['title']; ?></h2>
        <p><?php echo $course['description']; ?></p>
        <div class="course-meta">
            <p><strong>Duration:</strong> <?php echo $course['duration']; ?></p>
            <p><strong>Instructor:</strong> <?php echo $course['instructor']; ?></p>
        </div>
        <a href="student_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>
