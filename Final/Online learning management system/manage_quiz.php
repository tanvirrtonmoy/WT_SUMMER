<?php
session_start();

// Only admin check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php";

$successMsg = "";

// Delete quiz
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    // Delete quiz and associated questions from the database
    $sql = "DELETE FROM questions WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();

    // Delete the quiz from the database
    $sql = "DELETE FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        $successMsg = "🗑️ Quiz deleted successfully!";
    } else {
        $successMsg = "❌ Error deleting quiz: " . mysqli_error($conn);
    }
}

// Fetch quizzes from the database
$quizzes = [];
$sql = "SELECT * FROM quizzes";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $quizzes[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Quizzes</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; margin: auto; width: 80%; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn { padding: 6px 10px; text-decoration: none; border-radius: 5px; }
        .btn-edit { background: #3498db; color: #fff; }
        .btn-delete { background: #e74c3c; color: #fff; }
        .btn-back { background: #2c3e50; color: #fff; display: inline-block; margin-bottom: 10px; }
        .success { color: green; }
        details { margin: 10px 0; }
    </style>
</head>
<body>
<div class="card">
    <h2>📋 Manage Quizzes</h2>
    <?php if ($successMsg) echo "<p class='success'>$successMsg</p>"; ?>
    
    <a href="admin_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    <a href="create_quiz.php" class="btn btn-edit">➕ Create New Quiz</a>

    <?php if (empty($quizzes)): ?>
        <p>No quizzes created yet.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Course</th>
                <th>Title</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?php echo $quiz['id']; ?></td>
                    <td>
                        <?php
                        // Get the course name for this quiz
                        $courseName = "Unknown";
                        $courseSql = "SELECT title FROM courses WHERE id = ?";
                        $courseStmt = $conn->prepare($courseSql);
                        $courseStmt->bind_param("i", $quiz['course_id']);
                        $courseStmt->execute();
                        $courseResult = $courseStmt->get_result();
                        if ($courseRow = $courseResult->fetch_assoc()) {
                            $courseName = $courseRow['title'];
                        }
                        echo $courseName;
                        ?>
                    </td>
                    <td><?php echo $quiz['title']; ?></td>
                    <td><?php echo $quiz['quiz_date']; ?></td>
                    <td>
                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-edit">✏️ Edit</a>
                        <a href="?delete=<?php echo $quiz['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this quiz?');">🗑️ Delete</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <details>
                            <summary>🔍 View Questions</summary>
                            <?php
                            // Fetch questions for the quiz
                            $questionsSql = "SELECT * FROM questions WHERE quiz_id = ?";
                            $questionsStmt = $conn->prepare($questionsSql);
                            $questionsStmt->bind_param("i", $quiz['id']);
                            $questionsStmt->execute();
                            $questionsResult = $questionsStmt->get_result();
                            if ($questionsResult->num_rows > 0):
                            ?>
                                <ol>
                                    <?php while ($q = $questionsResult->fetch_assoc()): ?>
                                        <li>
                                            <b><?php echo $q['question_text']; ?></b><br>
                                            A) <?php echo $q['option_a']; ?><br>
                                            B) <?php echo $q['option_b']; ?><br>
                                            C) <?php echo $q['option_c']; ?><br>
                                            D) <?php echo $q['option_d']; ?><br>
                                            ✅ Correct: <?php echo strtoupper($q['correct_answer']); ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ol>
                            <?php else: ?>
                                <p>No questions added.</p>
                            <?php endif; ?>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
