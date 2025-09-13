<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 0;
            background: #f4f6f9;
        }

        /* Header on top */
        header {
            background: #2c3e50;
            color: #fff;
            padding: 15px 20px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        header nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        /* Sidebar below header */
        .sidebar {
            width: 220px;
            height: calc(100vh - 60px); /* adjust for header height */
            background: #34495e;
            color: #fff;
            position: fixed;
            top: 60px; /* below header */
            left: 0;
            padding-top: 20px;
            box-sizing: border-box;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #1abc9c;
        }

        /* Main content */
        .main {
            margin-left: 220px; /* sidebar width */
            padding: 80px 20px 20px 20px; /* top padding to avoid header overlap */
            box-sizing: border-box;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 20px;
            color: #2c3e50;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background: #1abc9c;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #16a085;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome Admin, <?php echo $_SESSION['user_name']; ?> 👨‍🏫</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="admin_profile.php"> Profile</a>
            <a href="admin_view_contact.php"><i class="fa-solid fa-bell"></i></a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>   

    <div class="sidebar">
        <a href="#courses"><i class="fa-solid fa-book"></i> Manage Courses</a>
        <a href="#materials"><i class="fa-solid fa-file-upload"></i> Upload Materials</a>
        <a href="#quizzes"><i class="fa-solid fa-clipboard-question"></i> Schedule Quizzes</a>
        <a href="#marks"><i class="fa-solid fa-marker"></i> Assign Marks</a>
        <a href="#progress"><i class="fa-solid fa-chart-line"></i> Monitor Progress</a>
        <a href="#announcements"><i class="fa-solid fa-bullhorn"></i> Announcements</a>
        <a href="#feedback"><i class="fa-solid fa-comments"></i> View Feedback</a>
    </div>

    <div class="main">

        <div id="courses" class="card">
            <h2>📘 Manage Courses</h2>
            <p>Create course, show course materials or  view all courses.</p>
            <a href="create_course.php"><button>Add New Course</button></a>
            <a href="view_courses_materials.php"><button>View All materials</button></a>

            <a href="view_courses.php"><button>View All Courses</button></a>
        </div>


        <div id="materials" class="card">
            <h2>📂 Upload Lecture Materials</h2>
            <p>Upload PDFs, videos, and lecture slides for students.</p>
            <a href="upload_material.php"><button>Upload Material</button></a> 
        </div>

        <div id="quizzes" class="card">
            <h2>📝 Schedule Quizzes</h2>
            <p>Create and schedule quizzes for different courses.</p>
            <a href="create_quiz.php"><button>Create Quiz</button></a>
            <a href="manage_quiz.php"><button>Manage Quizzes</button></a>
        </div>

        <div id="marks" class="card">
            <h2>✏️ Assign Marks</h2>
            <p>Assign marks for quizzes, assignments, and exams.</p>
            <button>Upload Marks</button>
        </div>

        <div id="progress" class="card">
            <h2>📊 Monitor Student Progress</h2>
            <p>View analytics, student leaderboard, and performance reports.</p>
            <button>View Analytics</button>
        </div>

        <div id="announcements" class="card">
            <h2>📢 Send Announcements</h2>
            <p>Send important updates or announcements to all students.</p>
            <a href="admin_send_announcement.php"><button>New Announcement</button></a>
        </div>

        <div id="feedback" class="card">
            <h2>💬 View Student Feedback</h2>
            <p>Check feedback submitted by students regarding courses, teachers, or the platform.</p>
            <a href="admin_view_feedback.php"><button>View Feedback</button></a>
        </div>

    </div>
</body>
</html>
