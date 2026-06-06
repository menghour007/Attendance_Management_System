<?php
// courses/edit_course.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Course.php';

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') { header("Location: ../Auth/login.php"); exit; }

if (!isset($_GET['id'])) { header("Location: courses.php"); exit; }
$id = $_GET['id'];

$database = new Db();
$manager = new Course($database->getConnection());
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($manager->updateCourse($id, $_POST['course_code'], $_POST['course_name'])) {
        header("Location: courses.php"); // Redirect back to list on success
        exit;
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Error updating course.</div>";
    }
}
$course = $manager->getCourseById($id);
if (!$course) { die("Course not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Course</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);}
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; box-sizing: border-box; }
        button { padding: 10px 15px; background: #2ecc71; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;}
    </style>
</head>
<body>
    <?php include_once '../dashboard/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Edit Course</h2>
            <?php echo $message; ?>
            <form method="POST">
                <label>Course Code</label>
                <input type="text" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
                
                <label>Course Name</label>
                <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                
                <button type="submit">Update Course</button>
                <a href="courses.php" style="margin-left: 15px; color: #7f8c8d; text-decoration: none;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>