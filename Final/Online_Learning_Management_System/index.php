<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduLearn - Online Learning Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i> EduLearn
            </div>
            <nav>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#user-types">User Types</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Advanced Online Learning Management System</h1>
            <p>Empowering educators and students with a comprehensive platform for teaching, learning, and collaboration.</p>
            <a href="login.php" class="btn">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Key Features</h2>
                <p>Our platform offers a wide range of features to enhance the learning experience for both instructors and students.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-book"></i></div>
                    <h3>Course Management</h3>
                    <p>Create, manage, and organize courses with ease. Upload materials, set schedules, and track progress.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-tasks"></i></div>
                    <h3>Assignments & Exams</h3>
                    <p>Create assignments and exams, set deadlines, and automatically grade submissions.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Progress Tracking</h3>
                    <p>Monitor student progress with detailed analytics and visual reports for better insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Types Section -->
    <section class="user-types" id="user-types">
        <div class="container">
            <div class="section-title">
                <h2>Designed for All Users</h2>
                <p>Our platform provides specialized features for different types of users.</p>
            </div>
            <div class="user-cards">
                <div class="user-card">
                    <div class="user-card-header"><h3>Admin / Instructor</h3></div>
                    <div class="user-card-body">
                        <ul>
                            <li>Create and manage courses</li>
                            <li>Upload lecture materials</li>
                            <li>Schedule exams and quizzes</li>
                            <li>Generate question papers</li>
                            <li>Assign marks and grades</li>
                            <li>Monitor student progress</li>
                            <li>Send announcements</li>
                        </ul>
                    </div>
                </div>
                <div class="user-card">
                    <div class="user-card-header"><h3>Student</h3></div>
                    <div class="user-card-body">
                        <ul>
                            <li>Enroll in courses</li>
                            <li>View course materials</li>
                            <li>Attempt exams and quizzes</li>
                            <li>Submit assignments</li>
                            <li>View results and grades</li>
                            <li>Track personal progress</li>
                            <li>Rate and review courses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container footer-content">
            <div class="logo"><i class="fas fa-graduation-cap"></i> EduLearn</div>
            <p>&copy; 2025 EduLearn. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
