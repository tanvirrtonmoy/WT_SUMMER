<?php
session_start();

// Only student check
if (!isset($_SESSION['loggedin']) || $_SESSION['user_role'] !== "student") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduLearn</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-user-graduate"></i> Student Dashboard
            </div>
            <nav>
                 <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="student_results.php"><i class="fas fa-chart-bar"></i> Results</a></li>
                    <li><a href="student_progress.php"><i class="fas fa-chart-line"></i> Progress</a></li>
                    <li><a href="student_show_notifications.php"><i class="fas fa-bell notification-icon"></i></a></li>
                    <li><a href="student_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome, <?php echo $_SESSION['user_name']; ?> 🎓</h1>
            <p>Access your courses, attempt quizzes, and track your learning journey all in one place.</p>
        </div>
    </section>

    <!-- Student Features -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>📌 Student Features</h2>
                <p>Everything you need as a student to learn, grow, and track your performance.</p>
            </div>
            <div class="features-grid">

                <!-- Enroll in Courses -->
                <div class="feature-card" id="courses">
                    <div class="feature-icon"><i class="fas fa-book-open"></i></div>
                    <h3>Enroll in Courses</h3>
                    <p>Browse and enroll in available courses offered by your instructors.</p>
                    <a href="student_enroll.php" class="btn">Enroll Now</a>
                </div>

                <!-- View Materials -->
                <div class="feature-card" id="materials">
                    <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                    <h3>View Materials</h3>
                    <p>Access lectures, PDFs, videos, and other learning resources anytime.</p>
                    <a href="student_materials.php" class="btn">View Materials</a>
                </div>

                <!-- Attempt Quizzes -->
                <div class="feature-card" id="quizzes">
                    <div class="feature-icon"><i class="fas fa-pen-to-square"></i></div>
                    <h3>Attempt Quizzes</h3>
                    <p>Take quizzes scheduled for your enrolled courses and test your knowledge.</p>
                    <a href="sudent_attempt_quiz.php" class="btn">Start Quiz</a>
                </div>

                <!-- View Results -->
                <div class="feature-card" id="results">
                    <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                    <h3>View Results</h3>
                    <p>Check your quiz and exam results, along with detailed feedback.</p>
                    <a href="student_results.php" class="btn">View Results</a>
                </div>

                <!-- Track Progress -->
                <div class="feature-card" id="progress">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Track Progress</h3>
                    <p>Monitor your performance with analytics and progress reports.</p>
                    <a href="student_progress.php" class="btn">View Dashboard</a>
                </div>

                <!-- Rate & Review -->
                <div class="feature-card" id="review">
                    <div class="feature-icon"><i class="fas fa-star"></i></div>
                    <h3>Rate & Review Courses</h3>
                    <p>Share your feedback on courses and help improve the learning experience.</p>
                    <a href="#feedback-section" class="btn">Give Feedback</a>
                </div>

                <!-- Report Issues -->
                <div class="feature-card" id="report">
                    <div class="feature-icon"><i class="fas fa-bug"></i></div>
                    <h3>Report Issues</h3>
                    <p>Report any technical or academic issues to the admin for quick resolution.</p>
                    <a href="student_report.php" class="btn">Report Issue</a>
                </div>

            </div>
        </div>
    </section>


        <!-- Courses Section -->
    <section class="courses" id="courses-section">
        <div class="container">
            <div class="section-title">
                <h2>📚 Available Courses</h2>
                <p>Browse through the latest courses and start your learning journey.</p>
            </div>

            <div class="features-grid">
                <!-- Course Card 1 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>Web Development</h3>
                    <p>Learn HTML, CSS, JavaScript and build modern websites.</p>
                    <a href="student_course_details.php?id=1" class="btn">View Course</a>
                </div>

                <!-- Course Card 2 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-database"></i></div>
                    <h3>Database Systems</h3>
                    <p>Master SQL and database design for scalable applications.</p>
                    <a href="student_course_details.php?id=2" class="btn">View Course</a>
                </div>

                <!-- Course Card 3 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-microchip"></i></div>
                    <h3>Embedded Systems</h3>
                    <p>Explore microcontrollers and real-time embedded applications.</p>
                    <a href="student_course_details.php?id=3" class="btn">View Course</a>
                </div>

                <!-- Course Card 4 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-network-wired"></i></div>
                    <h3>Computer Networks</h3>
                    <p>Understand networking protocols and secure communication.</p>
                    <a href="student_course_details.php?id=4" class="btn">View Course</a>
                </div>

                <!-- Course Card 5 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-brain"></i></div>
                    <h3>Artificial Intelligence</h3>
                    <p>Dive into AI, machine learning and intelligent systems.</p>
                    <a href="student_course_details.php?id=5" class="btn">View Course</a>
                </div>

                <!-- Course Card 6 -->
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lock"></i></div>
                    <h3>Cyber Security</h3>
                    <p>Learn how to protect systems from modern cyber threats.</p>
                    <a href="student_course_details.php?id=6" class="btn">View Course</a>
                </div>
            </div>

            <!-- View All Button -->
            <div style="text-align:center; margin-top:20px;">
                <a href="student_all_courses.php" class="btn">View All Courses</a>
            </div>
        </div>
    </section>

 



    <!-- Feedback Form Section -->
    <section class="feedback" id="feedback-section">
        <div class="container">
            <div class="section-title">
                <h2>📝 Share Your Feedback</h2>
                <p>We value your opinion! Help us improve your learning experience.</p>

                 <!-- ✅ Success/Error message will show here -->
            <?php
            if (isset($_SESSION['feedback_status'])) {
                if ($_SESSION['feedback_status'] === 'success') {
                    echo "<p style='color:green; font-weight:bold;'>✅ Feedback submitted successfully!</p>";
                } else {
                    echo "<p style='color:red; font-weight:bold;'>❌ Failed to submit feedback. Please try again!</p>";
                }
                unset($_SESSION['feedback_status']); // clear message after showing
            }
            ?>
            </div>

            <form action="student_feedback_submit.php" method="POST" class="feedback-form">
                <!-- Name -->
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Your Name</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo $_SESSION['user_name']; ?>" readonly>
                </div>

                <!-- Course -->
                <div class="form-group">
                    <label for="course"><i class="fas fa-book"></i> Course</label>
                    <select id="course" name="course" required>
                        <option value="">-- Select a Course --</option>
                        <option value="Web Development">Web Development</option>
                        <option value="Database Systems">Database Systems</option>
                        <option value="Embedded Systems">Embedded Systems</option>
                        <option value="Computer Networks">Computer Networks</option>
                        <option value="Artificial Intelligence">Artificial Intelligence</option>
                        <option value="Cyber Security">Cyber Security</option>
                    </select>
                </div>

                <!-- Rating -->
                <div class="form-group">
                    <label><i class="fas fa-star"></i> Rating</label>
                    <div class="rating">
                        <label><input type="radio" name="rating" value="5"> ⭐⭐⭐⭐⭐</label>
                        <label><input type="radio" name="rating" value="4"> ⭐⭐⭐⭐</label>
                        <label><input type="radio" name="rating" value="3"> ⭐⭐⭐</label>
                        <label><input type="radio" name="rating" value="2"> ⭐⭐</label>
                        <label><input type="radio" name="rating" value="1"> ⭐</label>
                    </div>
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label for="message"><i class="fas fa-comment"></i> Feedback</label>
                    <textarea id="message" name="message" rows="6" cols="60" required placeholder="Write your feedback..."></textarea>
                </div>

                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
                </div>
            </form>
        </div>
    </section>

    


        <!-- Contact Us Section -->
    <section class="contact" id="contact-section">
        <div class="container">
            <div class="section-title">
                <h2>📩 Contact Us</h2>
                <p>Have questions or need support? Send us a message and we’ll get back to you soon.</p>

                <?php
if (isset($_SESSION['contact_status'])) {
    if ($_SESSION['contact_status'] === 'success') {
        echo "<p style='color:green; font-weight:bold;'>✅ Message sent successfully!</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>❌ Message not sent. Please fill all fields!</p>";
    }
    unset($_SESSION['contact_status']); 
}
?>

            </div>

            <form action="student_contact_submit.php" method="POST" class="contact-form">
                <!-- Name -->
                <div class="form-group">
                    <label for="contact_name"><i class="fas fa-user"></i> Your Name</label>
                    <input type="text" id="contact_name" name="contact_name" 
                           value="<?php echo $_SESSION['user_name']; ?>" readonly>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="contact_email"><i class="fas fa-envelope"></i> Your Email</label>
                    <input type="email" id="contact_email" name="contact_email"  value="<?php echo $_SESSION['user_email']; ?>" readonly>
                
                </div>

                <!-- Subject -->
                <div class="form-group">
                    <label for="contact_subject"><i class="fas fa-tag"></i> Subject</label>
                    <input type="text" id="contact_subject" name="contact_subject" required placeholder="Enter subject">
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label for="contact_message"><i class="fas fa-comment-dots"></i> Message</label>
                    <textarea id="contact_message" name="contact_message" rows="6" cols="60" required placeholder="Write your message..."></textarea>
                </div>

                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Send Message</button>
                </div>
            </form>
        </div>
    </section>




    <!-- Footer -->
    <footer id="contact">
        <div class="container footer-content">
            <div class="logo"><i class="fas fa-user-graduate"></i> EduLearn</div>
            <p>&copy; 2025 EduLearn. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


