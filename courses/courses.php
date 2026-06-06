<?php
// courses/courses.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Course.php';

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header("Location: ../Auth/login.php");
    exit;
}

$database = new Db();
$db = $database->getConnection();
$manager = new Course($db);
$message = "";

// --- DELETE LOGIC ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($manager->deleteCourse($_GET['id'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Course deleted successfully!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Failed to delete course.</div>";
    }
}

// --- CREATE LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    if ($manager->addCourse($_POST['course_code'], $_POST['course_name'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Course added successfully!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Failed to add course. Code might already exist.</div>";
    }
}

$courses = $manager->getCourses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Courses</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;}
        input[type="text"] { padding: 8px; margin-right: 10px; width: 200px;}
        button { padding: 9px 15px; background: #3498db; color: white; border: none; cursor: pointer; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eaeded; }
        th { background-color: #f8f9fa; }
        .btn-edit { color: #3498db; text-decoration: none; font-weight: bold; margin-right: 15px; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <?php include_once '../dashboard/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Add New Course</h2>
            <?php echo $message; ?>
            <form method="POST" action="courses.php">
                <input type="text" name="course_code" placeholder="Course Code (e.g., CS101)" required>
                <input type="text" name="course_name" placeholder="Course Name (e.g., Intro to Programming)" required>
                <button type="submit" name="add_course">Save Course</button>
            </form>
        </div>

        <div class="card">
            <h2>Existing Courses</h2>
            <table>
                <tr><th>ID</th><th>Course Code</th><th>Course Name</th><th>Actions</th></tr>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo $course['id']; ?></td>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td>
                        <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="btn-edit">Edit</a>
                        <a href="courses.php?action=delete&id=<?php echo $course['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this course? This will also delete any classes tied to it!');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>