<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}

include "config.php";

// Selected IDs
$course_id  = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$chapter_id = isset($_GET['chapter_id']) ? intval($_GET['chapter_id']) : 0;
$topic_id   = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;

// Fetch all courses (header)
$courses = mysqli_query($conn, "SELECT * FROM courses");

// Fetch chapters for the selected course
$chapters = [];
if ($course_id) {
    $chapters = mysqli_query($conn, "SELECT * FROM chapters WHERE course_id=$course_id");
}

// Fetch material for selected topic
$material = null;
if ($topic_id) {
    $materialQuery = mysqli_query($conn, "SELECT m.*, c.title as course_title, ch.title as chapter_title, t.title as topic_title
        FROM materials m
        JOIN courses c ON m.course_id=c.id
        JOIN chapters ch ON m.chapter_id=ch.id
        JOIN topics t ON m.topic_id=t.id
        WHERE m.topic_id=$topic_id LIMIT 1");
    $material = mysqli_fetch_assoc($materialQuery);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enroll</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin:0; 
            height:100vh; 
            display:flex; 
            flex-direction:column; 
        }

        header { 
            background:#2c3e50; 
            color:#fff; 
            padding:10px; 
            display:flex; 
            align-items:center;
        }

        .header-links {
            display:flex;
            gap:15px;
            flex:1; /* push dashboard button to right */
        }

        header a { 
            color:#fff; 
            text-decoration:none; 
            font-weight:bold; 
            padding:5px 10px; 
            border-radius:5px;
        }

        header a:hover { 
            background:#1abc9c; 
        }

        header .active-course {
            background:#1abc9c;
        }

        .dashboard-btn {
            background:#e67e22; 
        }

        .main { 
            flex:1; 
            display:flex; 
            width:100%; 
        }

        .sidebar { 
            width:250px; 
            background:#f4f6f9; 
            padding:15px; 
            overflow-y:auto; 
            border-right:1px solid #ddd; 
        }

        .content { 
            flex:1; 
            padding:20px; 
            overflow-y:auto; 
        }

        h3 { margin:10px 0; color:#2c3e50; }

        ul { list-style:none; padding-left:15px; }

        ul li { margin:5px 0; }

        ul li a { text-decoration:none; color:#333; padding:3px 6px; display:block; border-radius:4px; }

        ul li a:hover { color:#1abc9c; }

        ul li a.active-topic {
            background:#1abc9c;
            color:#fff;
            font-weight:bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-links">
            <?php while ($c = mysqli_fetch_assoc($courses)): ?>
                <a href="student_enroll.php?course_id=<?= $c['id'] ?>" 
                   class="<?= ($c['id'] == $course_id) ? 'active-course' : '' ?>">
                    <?= htmlspecialchars($c['title']) ?>
                </a>
            <?php endwhile; ?>
        </div>
        <a href="student_dashboard.php" class="dashboard-btn">🏠 Dashboard</a>
    </header>

    <div class="main">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php if ($course_id): ?>
                <?php while ($ch = mysqli_fetch_assoc($chapters)): ?>
                    <h3><?= htmlspecialchars($ch['title']) ?></h3>
                    <ul>
                        <?php
                        $topics = mysqli_query($conn, "SELECT * FROM topics WHERE chapter_id={$ch['id']}");
                        while ($t = mysqli_fetch_assoc($topics)): ?>
                            <li>
                                <a href="student_enroll.php?course_id=<?= $course_id ?>&chapter_id=<?= $ch['id'] ?>&topic_id=<?= $t['id'] ?>"
                                   class="<?= ($t['id'] == $topic_id) ? 'active-topic' : '' ?>">
                                    <?= htmlspecialchars($t['title']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endwhile; ?>
            <?php else: ?>
                <p>📚 Select a course to view content</p>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="content">
            <?php if ($material): ?>
                <h2>
                    <?= htmlspecialchars($material['course_title']) ?> → 
                    <?= htmlspecialchars($material['chapter_title']) ?> → 
                    <?= htmlspecialchars($material['topic_title']) ?>
                </h2>
                <div><?= $material['content'] ?></div>

                <?php if (!empty($material['video_url'])): ?>
                    <?php 
                        $videoUrl = $material['video_url'];
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false): 
                            // YouTube embed
                            if (strpos($videoUrl, 'youtu.be') !== false) {
                                $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
                                $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                            } elseif (preg_match('/v=([^&]+)/', $videoUrl, $matches)) {
                                $videoId = $matches[1];
                                $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                            }
                            ?>
                            <iframe width="560" height="315" src="<?= htmlspecialchars($videoUrl) ?>" frameborder="0" allowfullscreen></iframe>
                        <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($material['file_path'])): ?>
                    <p><a href="<?= htmlspecialchars($material['file_path']) ?>" target="_blank">📂 Download File</a></p>
                <?php endif; ?>

            <?php else: ?>
                <p>👈 Select a topic to view details</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
