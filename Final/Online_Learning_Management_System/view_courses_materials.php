<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php";

// Fetch all the materials uploaded, including course cover image
$sql = "SELECT materials.*, topics.title AS topic_name, courses.title AS course_name, courses.cover_image 
        FROM materials 
        JOIN topics ON materials.topic_id = topics.id 
        JOIN courses ON materials.course_id = courses.id";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Materials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

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
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 80px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        td {
            background-color: #fff;
        }

        button {
            padding: 8px 15px;
            border: none;
            background: #1abc9c;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #16a085;
        }

    </style>
</head>
<body>

<header>
    <h1>View All Materials</h1>
    <nav>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </nav>
</header>

<div style="margin: 100px 20px;">
    <table>
        <thead>
            <tr>
                <th>File</th>
                <th>Type</th>
                <th>Topic</th>
                <th>Course</th>
                <th>URL Path</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $file_path = $row['file_path'];
                    $video_url = $row['video_url'];
                    $course_cover_image = $row['cover_image'];
                    $file_type = '';
                    $action = '';
                    $url_path = '';

                    // Determine file type based on the extension (for images and documents)
                    if (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $file_type = 'Picture';
                    } elseif (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), ['pdf'])) {
                        $file_type = 'PDF';
                    } elseif (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), ['doc', 'docx'])) {
                        $file_type = 'Document';
                    } elseif (in_array(strtolower(pathinfo($file_path, PATHINFO_EXTENSION)), ['ppt', 'pptx'])) {
                        $file_type = 'PPT';
                    }

                    // If video URL exists, it's a video
                    if ($video_url) {
                        $file_type = 'Video';
                        $url_path = $video_url;  // Display video URL in the URL path column
                        $action = "<a href='{$video_url}' target='_blank'><button>View Video</button></a>";
                    } else {
                        $url_path = $file_path;  // Regular file path for non-video materials
                        $action = "<a href='{$file_path}' download><button>Download File</button></a>";
                    }

                    echo "<tr>";
                    // Display course cover image in the "File" column
                    echo "<td><img src='{$course_cover_image}' alt='Course Image' width='50'></td>";
                    // Display the file type (image, document, etc.) in the "Type" column
                    echo "<td>{$file_type}</td>";
                    // Display the topic name in the "Topic" column
                    echo "<td>{$row['topic_name']}</td>";
                    // Display the course name in the "Course" column
                    echo "<td>{$row['course_name']}</td>";
                    // Display the video URL or file path in the "URL Path" column
                    echo "<td>{$url_path}</td>";
                    // Display the action (View Video or Download File) in the "Action" column
                    echo "<td>{$action}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No materials found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
