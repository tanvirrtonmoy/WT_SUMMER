<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}
include "config.php";

$student_id = $_SESSION['user_id'];

$sql = "
SELECT qr.score, qr.total, qr.created_at,
       q.title AS quiz_title,
       c.title AS chapter_title,
       co.title AS course_title
FROM quiz_results qr
JOIN quizzes q ON qr.quiz_id = q.id
JOIN chapters c ON q.chapter_id = c.id
JOIN courses co ON c.course_id = co.id
WHERE qr.student_id = ?
ORDER BY qr.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) $results[] = $row;
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Quiz Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: Arial; background:#f9f9f9; margin:0; padding:0; }
        header { background:#2c3e50; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center; }
        header h2 { margin:0; }
        header a { background:#1abc9c; color:#fff; padding:8px 12px; border-radius:5px; text-decoration:none; }
        .container { max-width:900px; margin:20px auto; background:#fff; padding:20px; border-radius:10px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { padding:10px; border:1px solid #ddd; text-align:left; }
        th { background:#2c3e50; color:#fff; }
        tr:nth-child(even) { background:#f2f2f2; }
        .btn { display:inline-block; background:#1abc9c; color:#fff; padding:8px 12px; border-radius:5px; text-decoration:none; }
        .btn:hover { background:#16a085; }
    </style>
</head>
<body>
<header>
    <h2>My Quiz Results</h2>
    <a href="student_dashboard.php">Dashboard</a>
</header>

<div class="container">
    <?php if(empty($results)): ?>
        <p>You have not attempted any quizzes yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Chapter</th>
                    <th>Quiz Title</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $i=>$r): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($r['course_title']) ?></td>
                        <td><?= htmlspecialchars($r['chapter_title']) ?></td>
                        <td><?= htmlspecialchars($r['quiz_title']) ?></td>
                        <td><?= $r['score'] ?> / <?= $r['total'] ?></td>
                        <td><?= $r['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
