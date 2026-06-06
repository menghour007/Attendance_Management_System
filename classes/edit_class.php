<?php
// classes/edit_class.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'ClassManager.php';
require_once '../courses/Course.php';
require_once '../academic_years/Academic.php';

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') { header("Location: ../Auth/login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: classes.php"); exit; }
$id = $_GET['id'];

$database = new Db();
$db = $database->getConnection();

$classManager = new ClassManager($db);
$courseManager = new Course($db);
$academicManager = new Academic($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($classManager->updateClass($id, $_POST['course_id'], $_POST['year_id'], $_POST['section_name'])) {
        header("Location: classes.php"); exit;
    }
}

$class = $classManager->getClassById($id);
if (!$class) { die("Class not found."); }

// Load all courses and academic years for the dropdown menus
$courses = $courseManager->getCourses();
$years = $academicManager->getAcademicYears();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Class</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);}
        input[type="text"], select { width: 100%; padding: 10px; margin-bottom: 15px; box-sizing: border-box; }
        button { padding: 10px 15px; background: #2ecc71; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;}
    </style>
</head>
<body>
    <?php include_once '../dashboard/sidebar.php'; ?>
    <div class="main-content">
        <div class="card">
            <h2>Edit Class</h2>
            <form method="POST">
                <label>Course</label>
                <select name="course_id" required>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php if($c['id'] == $class['course_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($c['course_code'] . " - " . $c['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label>Academic Year</label>
                <select name="year_id" required>
                    <?php foreach ($years as $y): ?>
                        <option value="<?php echo $y['id']; ?>" <?php if($y['id'] == $class['year_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($y['year_label'] . " (Sem " . $y['semester'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Section Name</label>
                <input type="text" name="section_name" value="<?php echo htmlspecialchars($class['section_name']); ?>" required>
                
                <button type="submit">Update Class</button>
                <a href="classes.php" style="margin-left: 15px; color: #7f8c8d; text-decoration: none;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>