<?php
session_start();
include("config.php"); // DB connection

// ✅ Only allow admin access
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// ✅ Fetch all student messages
$sql = "SELECT id, student_name, student_email, subject, message, created_at 
        FROM student_contact 
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Student Messages</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { width: 95%; margin: 30px auto; background: #fff; padding: 20px;
                     border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007BFF; color: white; }
        tr:hover { background: #f1f1f1; }
        .delete-btn {
            background: #dc3545; color: white; padding: 6px 12px;
            border: none; border-radius: 5px; cursor: pointer;
            text-decoration: none; font-size: 14px;
        }
        .delete-btn:hover { background: #b02a37; }
        .back-btn {
            display: inline-block; margin-top: 20px; padding: 10px 15px;
            background: #007BFF; color: white; text-decoration: none;
            border-radius: 5px; transition: 0.3s;
            display: inline-flex;
        }
        .back-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h2>📩 Student Messages</h2>

    <?php if (isset($_SESSION['delete_msg'])): ?>
        <p style="text-align:center; font-weight:bold; color:<?php echo $_SESSION['delete_msg']['type']; ?>;">
            <?php echo $_SESSION['delete_msg']['text']; ?>
        </p>
        <?php unset($_SESSION['delete_msg']); ?>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="delete_contact.php?id=<?php echo $row['id']; ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this message?');"> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:red;">❌ No messages submitted yet.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>
</body>
</html>
