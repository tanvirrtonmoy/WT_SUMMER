<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Include the database configuration
include('config.php');

// Initialize variables for form data
$title = $description = '';
$announcement_id = '';

// Check for POST request to handle adding, updating, or deleting an announcement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_announcement'])) {
        // Add a new announcement
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Insert announcement into the database
        $sql = "INSERT INTO announcements (title, description) VALUES ('$title', '$description')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Announcement added successfully!";
            $_SESSION['message_type'] = "success";  // Set message type
            // Clear the form data after adding
            $title = '';
            $description = '';
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            $_SESSION['message_type'] = "error";  // Set message type
        }
    }

    // Handle Update
    if (isset($_POST['update_announcement'])) {
        $announcement_id = $_POST['announcement_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Update the announcement
        $sql = "UPDATE announcements SET title='$title', description='$description' WHERE id='$announcement_id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Announcement updated successfully!";
            $_SESSION['message_type'] = "success";  // Set message type
            // Clear the form data after update
            $title = '';
            $description = '';
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            $_SESSION['message_type'] = "error";  // Set message type
        }
    }

    // Handle Delete
    if (isset($_POST['delete_announcement'])) {
        $announcement_id = $_POST['announcement_id'];

        // Delete the announcement
        $sql = "DELETE FROM announcements WHERE id='$announcement_id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Announcement deleted successfully!";
            $_SESSION['message_type'] = "success";  // Set message type
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            $_SESSION['message_type'] = "error";  // Set message type
        }
    }
}

// Fetch all announcements to display
$sql = "SELECT * FROM announcements";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Send Announcements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            padding: 10px 20px;
            background: #1abc9c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #16a085;
        }
        .announcement-table {
            width: 100%;
            border-collapse: collapse;
        }
        .announcement-table th, .announcement-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .announcement-table th {
            background-color: #2c3e50;
            color: white;
        }
        .announcement-table td button {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .update-btn {
            background: #f39c12;
            color: #fff;
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
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

        /* Success and Error Message Styles */
        .message {
            padding: 15px;
            background-color: #f4f6f9;
            color: #2c3e50;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            display: none;
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .message.show {
            display: block;
        }

        .message.success {
            background-color: #27ae60;
            color: white;
        }

        .message.error {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Send New Announcement</h2>
    <div class="form-container">
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required value="<?php echo ''; ?>" />
            <textarea name="description" placeholder="Description" rows="4" required><?php echo ''; ?></textarea>
            <button type="submit" name="add_announcement">Add Announcement</button>
            <!-- Back Button -->
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
        </form>
    </div>

    <!-- Message display section -->
    <?php if (isset($_SESSION['message'])) { ?>
        <div class="message <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?> show" id="message">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php
        // Unset the session message after it's displayed
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php } ?>

    <h2>Manage Announcements</h2>
    <table class="announcement-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <form method="POST">
                        <td><input type="text" name="title" value="<?php echo $row['title']; ?>" required /></td>
                        <td><textarea name="description" rows="4" required><?php echo $row['description']; ?></textarea></td>
                        <td>
                            <button type="submit" name="update_announcement" class="update-btn">Update</button>
                            <button type="submit" name="delete_announcement" class="delete-btn">Delete</button>
                            <input type="hidden" name="announcement_id" value="<?php echo $row['id']; ?>" />
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- JavaScript for automatically hiding the message after 5 seconds -->
<script>
    // If there's a message, fade it out after 5 seconds
    const message = document.getElementById('message');
    if (message) {
        setTimeout(function() {
            message.style.opacity = 0;
            setTimeout(function() {
                message.style.display = 'none';
            }, 1000); // Wait for the fade-out effect before hiding
        }, 5000); // Show the message for 5 seconds
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
