<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}

// Include the database configuration
include('config.php');

// Fetch all announcements for the student
$sql = "SELECT * FROM announcements ORDER BY created_at DESC"; // Assuming 'created_at' is the column storing the timestamp of announcement
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notifications - EduLearn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Internal CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
        }
        .container {
            width: 80%;
            margin: 50px auto;
        }

        /* Notifications Section */
        .notifications {
            padding: 20px 0;
        }

        .notifications h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .notification-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notification-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .notification-card p {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .notification-card small {
            font-size: 0.9rem;
            color: #888;
        }

        /* If no notifications */
        .notifications p {
            font-size: 1.2rem;
            color: #888;
        }

        /* Back Button Styling */
        .back-btn {
            padding: 10px 20px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .back-btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

    <!-- Notifications Section -->
    <section class="notifications">
        <div class="container">
            <h2>📢 Announcements</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="notifications-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="notification-card">
                            <h3><?php echo $row['title']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <small>Posted on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No new announcements.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Back Button -->
    <div class="container">
        <a href="student_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
