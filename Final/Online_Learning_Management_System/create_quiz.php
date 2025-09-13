<?php
session_start();

// Only admin check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

include "config.php";

// Ensure courses & quizzes arrays exist
$successMsg = "";
$chapters = [];
$courses = [];

// Get courses from the database
$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}

// Get chapters for the selected course (if any course is selected)
if (isset($_POST['course_id'])) {
    $course_id = (int)$_POST['course_id'];
    $sql = "SELECT * FROM chapters WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $chapters[] = $row;
    }
}

// Handle quiz creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {
    $course_id = (int)$_POST['course_id'];
    $chapter_id = (int)$_POST['chapter_id'];
    $quiz_title = trim($_POST['quiz_title']);
    $quiz_date = $_POST['quiz_date'];
    $questions = $_POST['questions'] ?? [];

    // Insert quiz into the database
    if ($course_id && $chapter_id && $quiz_title && $quiz_date && !empty($questions[0]['text'])) {
        // Insert quiz
        $sql = "INSERT INTO quizzes (course_id, chapter_id, title, quiz_date) 
                VALUES ('$course_id', '$chapter_id', '$quiz_title', '$quiz_date')";
        if (mysqli_query($conn, $sql)) {
            $quiz_id = mysqli_insert_id($conn); // Get the last inserted quiz ID

            // Insert questions into the database
            foreach ($questions as $q) {
                $question_text = mysqli_real_escape_string($conn, $q['text']);
                $option_a = mysqli_real_escape_string($conn, $q['a']);
                $option_b = mysqli_real_escape_string($conn, $q['b']);
                $option_c = mysqli_real_escape_string($conn, $q['c']);
                $option_d = mysqli_real_escape_string($conn, $q['d']);
                $correct_answer = mysqli_real_escape_string($conn, $q['answer']);
                
                $sql = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
                        VALUES ('$quiz_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";
                mysqli_query($conn, $sql);
            }
            $successMsg = "✅ Quiz '$quiz_title' created successfully with " . count($questions) . " question(s)!";
        } else {
            $successMsg = "❌ Error creating quiz: " . mysqli_error($conn);
        }
    } else {
        $successMsg = "❌ Please fill all fields including at least 1 question!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Quiz</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; width: 600px; margin: auto; }
        input, select, textarea { width: 100%; padding: 8px; margin: 8px 0; }
        button { padding: 8px 12px; background: #2c3e50; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #1abc9c; }
        .success { color: green; }
        .question-block { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="card">
    <h2>Create New Quiz</h2>
    <?php if ($successMsg) echo "<p class='success'>$successMsg</p>"; ?>

    <form method="post">
        <label>Select Course:</label>
        <select name="course_id" required onchange="this.form.submit()">
            <option value="">--Select Course--</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>" <?php echo isset($_POST['course_id']) && $_POST['course_id'] == $course['id'] ? 'selected' : ''; ?>><?php echo $course['title']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if (isset($_POST['course_id']) && !empty($chapters)): ?>
            <label>Select Chapter:</label>
            <select name="chapter_id" required>
                <option value="">--Select Chapter--</option>
                <?php foreach ($chapters as $chapter): ?>
                    <option value="<?php echo $chapter['id']; ?>" <?php echo isset($_POST['chapter_id']) && $_POST['chapter_id'] == $chapter['id'] ? 'selected' : ''; ?>><?php echo $chapter['title']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <label>Quiz Title:</label>
        <input type="text" name="quiz_title" required>

        <label>Date & Time:</label>
        <input type="datetime-local" name="quiz_date" required>

        <h3>Quiz Questions</h3>
        <div id="questions"></div>
        <button type="button" onclick="addQuestion()">➕ Add Question</button>
        <br><br>

        <button type="submit" name="create_quiz">Create Quiz</button>
    </form>
    <br>
    <a href="admin_dashboard.php">← Back to Dashboard</a>
</div>

<script>
    function addQuestion() {
        let container = document.getElementById("questions");
        let qIndex = container.children.length;
        let div = document.createElement("div");
        div.classList.add("question-block");
        div.innerHTML = `  
            <label>Question:</label>
            <textarea name="questions[${qIndex}][text]" required></textarea>
            <label>Option A:</label>
            <input type="text" name="questions[${qIndex}][a]" required>
            <label>Option B:</label>
            <input type="text" name="questions[${qIndex}][b]" required>
            <label>Option C:</label>
            <input type="text" name="questions[${qIndex}][c]" required>
            <label>Option D:</label>
            <input type="text" name="questions[${qIndex}][d]" required>
            <label>Correct Answer:</label>
            <select name="questions[${qIndex}][answer]" required>
                <option value="">--Select--</option>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
            </select>
        `;
        container.appendChild(div);
    }
</script>

</body>
</html>
