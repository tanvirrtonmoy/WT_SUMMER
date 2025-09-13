<?php
session_start();

// Only admin check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php";

// Get quiz ID from URL
$quizId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quiz = null;
$questions = [];

// Fetch quiz details from the database
if ($quizId) {
    $sql = "SELECT * FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $quiz = $row;
    }

    // Fetch questions related to the quiz
    $sql = "SELECT * FROM questions WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

// If quiz is not found
if (!$quiz) {
    echo "❌ Quiz not found!";
    exit;
}

$successMsg = "";

// Update quiz details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quiz'])) {
    $quiz_title = trim($_POST['quiz_title']);
    $quiz_date = $_POST['quiz_date'];

    if ($quiz_title && $quiz_date) {
        $sql = "UPDATE quizzes SET title = ?, quiz_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $quiz_title, $quiz_date, $quizId);
        if ($stmt->execute()) {
            $successMsg = "✅ Quiz updated successfully!";
        } else {
            $successMsg = "❌ Error updating quiz!";
        }
    } else {
        $successMsg = "❌ Please fill in all fields!";
    }
}

// Add new question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $qText = trim($_POST['q_text']);
    $optA = trim($_POST['opt_a']);
    $optB = trim($_POST['opt_b']);
    $optC = trim($_POST['opt_c']);
    $optD = trim($_POST['opt_d']);
    $ans  = $_POST['answer'];

    if ($qText && $optA && $optB && $optC && $optD && $ans) {
        $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $quizId, $qText, $optA, $optB, $optC, $optD, $ans);
        if ($stmt->execute()) {
            $successMsg = "✅ Question added!";
        } else {
            $successMsg = "❌ Error adding question!";
        }
    } else {
        $successMsg = "❌ Please fill all fields for the question!";
    }
}

// Delete question
if (isset($_GET['delete_q'])) {
    $qIndex = (int)$_GET['delete_q'];
    if (isset($questions[$qIndex])) {
        $questionId = $questions[$qIndex]['id'];
        $sql = "DELETE FROM questions WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        if ($stmt->execute()) {
            $successMsg = "🗑️ Question deleted!";
            unset($questions[$qIndex]); // Remove from local array
            $questions = array_values($questions); // Reindex array
        } else {
            $successMsg = "❌ Error deleting question!";
        }
    }
}

// Prepare for editing a question
$editIndex = isset($_GET['edit_q']) ? (int)$_GET['edit_q'] : -1;
$editQuestion = ($editIndex >= 0 && isset($questions[$editIndex])) ? $questions[$editIndex] : null;

// Handle question update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_question'])) {
    $idx = (int)$_POST['q_index'];
    if (isset($questions[$idx])) {
        $questions[$idx]['text'] = trim($_POST['q_text']);
        $questions[$idx]['a'] = trim($_POST['opt_a']);
        $questions[$idx]['b'] = trim($_POST['opt_b']);
        $questions[$idx]['c'] = trim($_POST['opt_c']);
        $questions[$idx]['d'] = trim($_POST['opt_d']);
        $questions[$idx]['answer'] = $_POST['answer'];

        $questionId = $questions[$idx]['id'];

        $sql = "UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $questions[$idx]['text'], $questions[$idx]['a'], $questions[$idx]['b'], $questions[$idx]['c'], $questions[$idx]['d'], $questions[$idx]['answer'], $questionId);
        
        if ($stmt->execute()) {
            $successMsg = "✏️ Question updated!";
        } else {
            $successMsg = "❌ Error updating question!";
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Quiz</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; margin: auto; width: 80%; }
        input, select { width: 100%; padding: 8px; margin: 6px 0; }
        button { padding: 8px 12px; background: #2c3e50; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #1abc9c; }
        .btn-delete { background: #e74c3c; padding: 5px 10px; }
        .btn-edit { background: #3498db; padding: 5px 10px; }
        .btn-back { background: #2c3e50; text-decoration: none; padding: 6px 12px; color: white; border-radius: 5px; }
        .success { color: green; }
        h3 { margin-top: 30px; }
        ol li { margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="card">
    <h2>✏️ Edit Quiz - <?php echo $quiz['title']; ?></h2>
    <?php if ($successMsg) echo "<p class='success'>$successMsg</p>"; ?>

    <!-- Update Quiz -->
    <form method="post">
        <label>Quiz Title:</label>
        <input type="text" name="quiz_title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>

        <label>Date & Time:</label>
        <input type="datetime-local" name="quiz_date" value="<?php echo $quiz['quiz_date']; ?>" required>

        <button type="submit" name="update_quiz">💾 Save Quiz</button>
    </form>

    <!-- Add Question -->
    <h3>➕ Add New Question</h3>
    <form method="post">
        <label>Question:</label>
        <input type="text" name="q_text" required>

        <label>Option A:</label>
        <input type="text" name="opt_a" required>
        <label>Option B:</label>
        <input type="text" name="opt_b" required>
        <label>Option C:</label>
        <input type="text" name="opt_c" required>
        <label>Option D:</label>
        <input type="text" name="opt_d" required>

        <label>Correct Answer:</label>
        <select name="answer" required>
            <option value="">--Select--</option>
            <option value="a">A</option>
            <option value="b">B</option>
            <option value="c">C</option>
            <option value="d">D</option>
        </select>

        <button type="submit" name="add_question">➕ Add Question</button>
    </form>

    <!-- Edit Existing Question -->
    <?php if ($editQuestion): ?>
        <h3>✏️ Edit Question</h3>
        <form method="post">
            <input type="hidden" name="q_index" value="<?php echo $editIndex; ?>">
            <label>Question:</label>
            <input type="text" name="q_text" value="<?php echo htmlspecialchars($editQuestion['question_text']); ?>" required>

            <label>Option A:</label>
            <input type="text" name="opt_a" value="<?php echo htmlspecialchars($editQuestion['option_a']); ?>" required>
            <label>Option B:</label>
            <input type="text" name="opt_b" value="<?php echo htmlspecialchars($editQuestion['option_b']); ?>" required>
            <label>Option C:</label>
            <input type="text" name="opt_c" value="<?php echo htmlspecialchars($editQuestion['option_c']); ?>" required>
            <label>Option D:</label>
            <input type="text" name="opt_d" value="<?php echo htmlspecialchars($editQuestion['option_d']); ?>" required>

            <label>Correct Answer:</label>
            <select name="answer" required>
                <option value="a" <?php if ($editQuestion['correct_answer'] == "a") echo "selected"; ?>>A</option>
                <option value="b" <?php if ($editQuestion['correct_answer'] == "b") echo "selected"; ?>>B</option>
                <option value="c" <?php if ($editQuestion['correct_answer'] == "c") echo "selected"; ?>>C</option>
                <option value="d" <?php if ($editQuestion['correct_answer'] == "d") echo "selected"; ?>>D</option>
            </select>

            <button type="submit" name="update_question">💾 Update Question</button>
        </form>
    <?php endif; ?>

    <!-- Existing Questions -->
    <h3>📋 Existing Questions</h3>
    <?php if (!empty($questions)): ?>
        <ol>
            <?php foreach ($questions as $i => $q): ?>
                <li>
                    <b><?php echo htmlspecialchars($q['question_text']); ?></b><br>
                    A) <?php echo htmlspecialchars($q['option_a']); ?><br>
                    B) <?php echo htmlspecialchars($q['option_b']); ?><br>
                    C) <?php echo htmlspecialchars($q['option_c']); ?><br>
                    D) <?php echo htmlspecialchars($q['option_d']); ?><br>
                    ✅ Correct: <?php echo strtoupper($q['correct_answer']); ?><br>
                    <a href="?id=<?php echo $quizId; ?>&edit_q=<?php echo $i; ?>" class="btn-edit">✏️ Edit</a>
                    <a href="?id=<?php echo $quizId; ?>&delete_q=<?php echo $i; ?>" class="btn-delete" onclick="return confirm('Delete this question?');">🗑️ Delete</a>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php else: ?>
        <p>No questions added yet.</p>
    <?php endif; ?>

    <br><br>
    <a href="manage_quiz.php" class="btn-back">← Back to Manage Quizzes</a>
</div>
</body>
</html>
