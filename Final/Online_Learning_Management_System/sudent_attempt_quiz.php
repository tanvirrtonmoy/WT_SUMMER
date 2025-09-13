<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}
include "config.php";

$courses = $chapters = [];
$quiz = null;
$questions = [];
$student_score = null;

// Fetch all courses
$result = mysqli_query($conn, "SELECT * FROM courses");
while ($row = mysqli_fetch_assoc($result)) $courses[] = $row;

// Load chapters when course selected
if (isset($_POST['course_id'])) {
    $course_id = (int)$_POST['course_id'];
    $stmt = $conn->prepare("SELECT * FROM chapters WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $chapters[] = $row;
}



// Load quiz + questions when chapter selected
if (isset($_POST['chapter_id'])) {
    $chapter_id = (int)$_POST['chapter_id'];
    $stmt = $conn->prepare("SELECT * FROM quizzes WHERE chapter_id = ? ORDER BY quiz_date DESC LIMIT 1");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $quiz = $stmt->get_result()->fetch_assoc();

    if ($quiz) {
        $quiz_id = $quiz['id'];  // Ensure quiz_id is set if quiz is found
        echo "Quiz ID: " . $quiz_id . "<br>";  // Debugging: Show quiz ID
        
        // Store quiz_id in the session
        $_SESSION['quiz_id'] = $quiz_id;  // Store quiz_id in session

        $qres = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = $quiz_id");
        while ($row = mysqli_fetch_assoc($qres)) $questions[] = $row;
    } else {
        echo "No quiz found for this chapter.<br>";  // Debugging: Show if no quiz is found
    }
}

// Handle quiz submission
if (isset($_POST['submit_quiz']) && isset($_POST['answers'])) {
    $answers = $_POST['answers'];
    $score = 0;
    $total = count($answers);

    foreach ($answers as $qid => $ans) {
        $res = mysqli_query($conn, "SELECT correct_answer FROM questions WHERE id = $qid");
        $row = mysqli_fetch_assoc($res);
        if ($row && $row['correct_answer'] == $ans) {
            $score++;
        }
    }

    $student_id = $_SESSION['user_id'];

    // Retrieve quiz_id from the session
    if (isset($_SESSION['quiz_id'])) {
        $quiz_id = $_SESSION['quiz_id'];  // Get quiz_id from the session

        // Use prepared statements for secure query execution
        $stmt = $conn->prepare("INSERT INTO quiz_results (student_id, quiz_id, score, total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $student_id, $quiz_id, $score, $total);

        // Execute the query
        if ($stmt->execute()) {
            $student_score = "You scored $score out of $total.";
        } else {
            $student_score = "Error: " . $stmt->error; // Handle execution error
        }
        $stmt->close(); // Close prepared statement
    } else {
        $student_score = "Quiz not found. Please try again.";  // If quiz_id is not found in session
    }
}



?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Quizzes</title>
    <style>
        body { font-family: Arial; background:#f9f9f9; margin:0; padding:0; }
        header { background:#2c3e50; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center; }
        header h2 { margin:0; } header a { background:#1abc9c; color:#fff; padding:8px 12px; border-radius:5px; text-decoration:none; }
        .container { max-width:800px; margin:20px auto; background:#fff; padding:20px; border-radius:10px; }
        label { font-weight:bold; } select, button { width:100%; padding:8px; margin:10px 0; }
        button { background:#2c3e50; color:#fff; border:none; cursor:pointer; } button:hover { background:#1abc9c; }
        .question { display:flex; align-items:center; margin:15px 0; }
        .q-circle { width:30px; height:30px; border-radius:50%; background:#2c3e50; color:#fff; display:flex; justify-content:center; align-items:center; font-weight:bold; margin-right:15px; }
        .options label { display:block; margin:5px 0; padding:5px; border:1px solid #ddd; border-radius:5px; cursor:pointer; }
        .options input { margin-right:8px; } .score { font-size:18px; font-weight:bold; color:green; margin-top:20px; }
    </style>
</head>
<body>
<header>
    <h2>Start Quizzes</h2>
    <a href="student_dashboard.php">Dashboard</a>
</header>

<div class="container">
    <form method="post">
        <label>Select Course:</label>
        <select name="course_id" onchange="this.form.submit()">
            <option value="">--Select Course--</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>" <?= isset($_POST['course_id']) && $_POST['course_id']==$c['id'] ? 'selected':'' ?>>
                    <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($chapters)): ?>
            <label>Select Chapter:</label>
            <select name="chapter_id" onchange="this.form.submit()">
                <option value="">--Select Chapter--</option>
                <?php foreach ($chapters as $ch): ?>
                    <option value="<?= $ch['id'] ?>" <?= isset($_POST['chapter_id']) && $_POST['chapter_id']==$ch['id'] ? 'selected':'' ?>>
                        <?= htmlspecialchars($ch['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </form>

    <?php if ($quiz && !empty($questions)): ?>
        <h3><?= htmlspecialchars($quiz['title']) ?></h3>
        <form method="post">
            <?php foreach ($questions as $i=>$q): ?>
                <div class="question">
                    <div class="q-circle"><?= $i+1 ?></div>
                    <div class="options">
                        <p><?= htmlspecialchars($q['question_text']) ?></p>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="a" required> <?= htmlspecialchars($q['option_a']) ?></label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="b"> <?= htmlspecialchars($q['option_b']) ?></label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="c"> <?= htmlspecialchars($q['option_c']) ?></label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="d"> <?= htmlspecialchars($q['option_d']) ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" name="submit_quiz">Submit Quiz</button>
        </form>
    <?php elseif(isset($_POST['chapter_id'])): ?>
        <p>No quiz found for this chapter.</p>
    <?php endif; ?>

    <?php if ($student_score): ?>
        <p class="score"><?= $student_score ?></p>
    <?php endif; ?>

    <!-- View Results -->
    <div class="feature-card" id="results" style="margin-top:20px; text-align:center;">
        <a href="student_results.php" class="btn">View Results</a>
    </div>
</div>
</body>
</html>
