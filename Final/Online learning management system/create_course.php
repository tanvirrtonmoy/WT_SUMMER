<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php"; // database connection

// Handle form submissions
if (isset($_POST['create_course'])) {
    $title = $_POST['course_title'];
    $description = $_POST['course_description'];

    // Check if the course already exists
    $checkCourse = mysqli_query($conn, "SELECT * FROM courses WHERE title = '$title'");

    if (mysqli_num_rows($checkCourse) > 0) {
        // If the course already exists, set the error message and redirect
        $_SESSION['msg'] = "❌ Course with this title already exists!";
        header("Location: create_course.php");
        exit;
    }

    // Handle image upload
    $cover_image = "";
    if (!empty($_FILES['cover_image']['name'])) {
        $targetDir = "uploads/courses/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $cover_image = $targetDir . time() . "_" . basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image);
    }

    // Insert new course into the database
    $sql = "INSERT INTO courses (title, description, cover_image) VALUES ('$title', '$description', '$cover_image')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "✅ Course created successfully!";
    } else {
        $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
    }

    // Redirect after creating course
    header("Location: create_course.php");
    exit;
}


// Create new chapter
if (isset($_POST['create_chapter'])) {
    $course_id = $_POST['course_id'];
    $chapter_title = $_POST['chapter_title'];

    // Check if chapter already exists for the selected course
    $checkChapter = mysqli_query($conn, "SELECT * FROM chapters WHERE course_id = '$course_id' AND title = '$chapter_title'");

    if (mysqli_num_rows($checkChapter) > 0) {
        $_SESSION['msg'] = "❌ A chapter with this title already exists in this course!";
        header("Location: create_course.php");
        exit;
    }

    // Insert new chapter
    $sql = "INSERT INTO chapters (course_id, title) VALUES ('$course_id', '$chapter_title')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "✅ Chapter created successfully!";
    } else {
        $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
    }
    header("Location: create_course.php");
    exit;
}

// Create new topic
if (isset($_POST['create_topic'])) {
    $course_id = $_POST['course_id'];
    $chapter_id = $_POST['chapter_id'];
    $topic_title = $_POST['topic_title'];

    // Check if topic already exists for the selected chapter and course
    $checkTopic = mysqli_query($conn, "SELECT * FROM topics WHERE course_id = '$course_id' AND chapter_id = '$chapter_id' AND title = '$topic_title'");

    if (mysqli_num_rows($checkTopic) > 0) {
        $_SESSION['msg'] = "❌ A topic with this title already exists in this chapter!";
        header("Location: create_course.php");
        exit;
    }

    // Insert new topic
    $sql = "INSERT INTO topics (course_id, chapter_id, title) VALUES ('$course_id', '$chapter_id', '$topic_title')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "✅ Topic created successfully!";
    } else {
        $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
    }
    header("Location: create_course.php");
    exit;
}

// Fetch courses and chapters for dropdowns
$courses = mysqli_query($conn, "SELECT * FROM courses");
$chapters = mysqli_query($conn, "SELECT * FROM chapters");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
            background: #f4f6f9;
        }
        header {
            background: #2c3e50;
            color: #fff;
            padding: 15px 20px;
            position: fixed; top: 0; left: 0; right: 0;
            display: flex; justify-content: space-between; align-items: center;
        }
        header h1 { margin: 0; font-size: 20px; }
        header nav a {
            color: #fff; margin-left: 15px; text-decoration: none; font-weight: bold;
        }
        .container {
            margin: 100px auto; padding: 20px;
            max-width: 800px;
        }
        .card {
            background: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card h2 { margin-top: 0; font-size: 20px; color: #2c3e50; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea, select {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            margin-top: 15px; padding: 10px 15px;
            background: #1abc9c; border: none; border-radius: 5px;
            color: #fff; font-weight: bold; cursor: pointer;
        }
        button:hover { background: #16a085; }
        .message { padding: 10px; background: #ecf0f1; margin-bottom: 20px; border-radius: 5px; }
    </style>
</head>
<body>
<header>
    <h1>Create Course Panel</h1>
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

    <!-- Section 1: Create Course -->
    <div class="card">
        <h2>📘 Create New Course</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Course Title</label>
            <input type="text" name="course_title" required>

            <label>Course Description</label>
            <textarea name="course_description" rows="3" required></textarea>

            <label>Cover Image</label>
            <input type="file" name="cover_image">

            <button type="submit" name="create_course">Create Course</button>
        </form>
    </div>

    <!-- Section 2: Create Chapter -->
    <div class="card">
        <h2>📂 Create New Chapter</h2>
        <form method="post">
            <label>Select Course</label>
            <select name="course_id" required>
                <option value="">-- Select Course --</option>
                <?php while ($c = mysqli_fetch_assoc($courses)): ?>
                    <option value="<?= $c['id']; ?>"><?= $c['title']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Chapter Title</label>
            <input type="text" name="chapter_title" required>

            <button type="submit" name="create_chapter">Create Chapter</button>
        </form>
    </div>

    <!-- Section 3: Create Topic -->
    <div class="card">
        <h2>📝 Create New Topic</h2>
        <form method="post">
            <label>Select Course</label>
            <select name="course_id" id="course_id" required onchange="this.form.submit()">
                <option value="">-- Select Course --</option>
                <?php
                $courses2 = mysqli_query($conn, "SELECT * FROM courses");
                while ($c = mysqli_fetch_assoc($courses2)): ?>
                    <option value="<?= $c['id']; ?>" <?= isset($_POST['course_id']) && $_POST['course_id'] == $c['id'] ? 'selected' : ''; ?>>
                        <?= $c['title']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Select Chapter</label>
            <select name="chapter_id" id="chapter_id" required>
                <option value="">-- Select Chapter --</option>
                <?php 
                if (isset($_POST['course_id'])) {
                    $course_id = $_POST['course_id'];
                    
                    // Fetch chapters for the selected course
                    $stmt = $conn->prepare("SELECT * FROM chapters WHERE course_id = ?");
                    $stmt->bind_param("i", $course_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>" <?= isset($_POST['chapter_id']) && $_POST['chapter_id'] == $row['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['title']); ?>
                        </option>
                    <?php endwhile;
                }
                ?>
            </select>

            <label>Topic Title</label>
            <input type="text" name="topic_title" required>

            <button type="submit" name="create_topic">Create Topic</button>
        </form>
    </div>





</div>

<script>
    // auto-hide message after 3s
    setTimeout(() => {
        let msg = document.getElementById("msg");
        if (msg) msg.style.display = "none";
    }, 3000);


    window.onload = function() {
    // Check if a course is selected and if there is a chapter dropdown
    if (document.getElementById('course_id').value !== "") {
        // Scroll to the chapter section
        document.getElementById('chapter_id').scrollIntoView({ behavior: "smooth" });
    }
    };
</script>

</body>
</html>
