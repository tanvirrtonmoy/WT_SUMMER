<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}
include "config.php";

// Handle save content
if (isset($_POST['save_content'])) {
    $course_id = $_POST['course_id'];
    $chapter_id = $_POST['chapter_id'];
    $topic_id = $_POST['topic_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
    $file_upload = $_FILES['file_upload'];

    // File upload handling
    if ($file_upload['error'] == 0) {
        $file_name = $file_upload['name'];
        $file_tmp = $file_upload['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . $file_ext;

        // Move the uploaded file to a designated folder
        if (move_uploaded_file($file_tmp, "uploads/$new_file_name")) {
            $file_path = "uploads/$new_file_name";
        } else {
            $_SESSION['msg'] = "❌ Error uploading file.";
            header("Location: upload_material.php");
            exit;
        }
    } else {
        $file_path = null; // No file uploaded
    }

    // Insert content into the database
    $sql = "INSERT INTO materials (course_id, chapter_id, topic_id, content, video_url, file_path) 
            VALUES ('$course_id', '$chapter_id', '$topic_id', '$content', '$video_url', '$file_path')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "✅ Content uploaded successfully!";
    } else {
        $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Material</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; margin:0; padding:0; }
        .navbar { background-color: #2c3e50; padding: 10px; }
        .navbar a { color: #fff; padding: 10px; text-decoration: none; font-weight: bold; }
        .navbar a:hover { background-color: #1abc9c; }
        .container { max-width: 800px; margin: 100px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-top:0; color:#2c3e50; }
        label { display:block; margin-top:10px; font-weight:bold; }
        select, input, button { width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px; }
        button { background:#1abc9c; color:#fff; font-weight:bold; cursor:pointer; }
        button:hover { background:#16a085; }
        .message { padding:10px; background:#ecf0f1; margin-bottom:20px; border-radius:5px; }
        #summernote-container { display:none; margin-top:20px; }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</div>


<!-- Section for Uploading Course Material -->
<div class="container">
    <h2>📂 Upload Course Material</h2>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="message"><?= $_SESSION['msg']; ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <!-- Select Course -->
        <label>Select Course</label>
        <select name="course_id" id="course_id" required onchange="this.form.submit()">
            <option value="">-- Select Course --</option>
            <?php
            $courses = mysqli_query($conn, "SELECT * FROM courses");
            while ($c = mysqli_fetch_assoc($courses)) {
                // Set the selected option if the course is selected
                $selected = (isset($_POST['course_id']) && $_POST['course_id'] == $c['id']) ? 'selected' : '';
                echo "<option value='{$c['id']}' {$selected}>{$c['title']}</option>";
            }
            ?>
        </select>

        <!-- Select Chapter (Dynamically loaded based on course) -->
        <label>Select Chapter</label>
        <select name="chapter_id" id="chapter_id" required onchange="this.form.submit()">
            <option value="">-- Select Chapter --</option>
            <?php
            if (isset($_POST['course_id'])) {
                $course_id = $_POST['course_id'];
                $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE course_id = '$course_id'");
                while ($ch = mysqli_fetch_assoc($chapters)) {
                    // Set the selected chapter if it's already selected
                    $selected = (isset($_POST['chapter_id']) && $_POST['chapter_id'] == $ch['id']) ? 'selected' : '';
                    echo "<option value='{$ch['id']}' {$selected}>{$ch['title']}</option>";
                }
            }
            ?>
        </select>

        <!-- Select Topic (Dynamically loaded based on chapter) -->
        <label>Select Topic</label>
        <select name="topic_id" id="topic_id" required>
            <option value="">-- Select Topic --</option>
            <?php
            if (isset($_POST['chapter_id'])) {
                $chapter_id = $_POST['chapter_id'];
                $topics = mysqli_query($conn, "SELECT * FROM topics WHERE chapter_id = '$chapter_id'");
                while ($t = mysqli_fetch_assoc($topics)) {
                    // Set the selected topic if it's already selected
                    $selected = (isset($_POST['topic_id']) && $_POST['topic_id'] == $t['id']) ? 'selected' : '';
                    echo "<option value='{$t['id']}' {$selected}>{$t['title']}</option>";
                }
            }
            ?>
        </select>

        <!-- Video URL and File Upload -->
        <label>Video URL</label>
        <input type="url" name="video_url" placeholder="Enter video URL (Optional)" />

        <label>Upload File</label>
        <input type="file" name="file_upload" accept="image/*, .pdf, .docx, .txt, .zip" />

        <button type="button" onclick="showEditor()">Load Content Editor</button>

        <!-- Hidden field to capture Summernote content -->
        <input type="hidden" name="content" id="contentField">

        <!-- Container for Summernote editor -->
        <div id="summernote-container">
            <h2>Course content</h2>
            <div id="summernote"></div>
            <button type="submit" name="save_content" id="saveBtn">Save Content</button>
        </div>
    </form>
</div>




<!-- Summernote -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<script>
    function showEditor() {
        $('#summernote-container').show();       // Show Summernote container
        $('#summernote').summernote({            // Initialize Summernote editor inside the div
            height: 400,
            callbacks: {
                onChange: function(contents, $editable) {
                    // Update the hidden input field with the content of Summernote
                    $('#contentField').val(contents);
                }
            }
        });
        $('#saveBtn').show();                    // Show save button
    }
</script>

</body>
</html>
