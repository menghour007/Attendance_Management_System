<?php
// classes/classes.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'ClassManager.php';
require_once '../courses/Course.php';          // Needed for dropdown
require_once '../academic_years/Academic.php'; // Needed for dropdown

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') { header("Location: ../Auth/login.php"); exit; }

$database = new Db();
$db = $database->getConnection();

$classManager = new ClassManager($db);
$courseManager = new Course($db);
$academicManager = new Academic($db);

$message = "";

// --- DELETE LOGIC ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($classManager->deleteClass($_GET['id'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Class deleted successfully!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Failed to delete class.</div>";
    }
}

// --- CREATE LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_class'])) {
    if ($classManager->addClass($_POST['course_id'], $_POST['year_id'], $_POST['section_name'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Class created successfully!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Error creating class.</div>";
    }
}

$classes = $classManager->getClasses();
$courses = $courseManager->getCourses();
$years = $academicManager->getAcademicYears();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Classes</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;}
        input[type="text"], select { padding: 8px; margin-right: 10px; width: 200px; margin-bottom: 10px;}
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
            <h2>Create a New Class</h2>
            <?php echo $message; ?>
            <form method="POST" action="classes.php">
                <select name="course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_code'] . " - " . $c['course_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="year_id" required>
                    <option value="">-- Select Academic Year --</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?php echo $y['id']; ?>"><?php echo htmlspecialchars($y['year_label'] . " (Sem " . $y['semester'] . ")"); ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="section_name" placeholder="Section Name" required>
                <br>
                <button type="submit" name="add_class">Create Class</button>
            </form>
        </div>

        <div class="card">
            <h2>Active Classes</h2>
            <table>
                <tr><th>ID</th><th>Course</th><th>Academic Year</th><th>Section</th><th>Actions</th></tr>
                <?php foreach ($classes as $cls): ?>
                <tr>
                    <td><?php echo $cls['id']; ?></td>
                    <td><?php echo htmlspecialchars($cls['course_code'] . " - " . $cls['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($cls['year_label'] . " (Sem " . $cls['semester'] . ")"); ?></td>
                    <td><strong><?php echo htmlspecialchars($cls['section_name']); ?></strong></td>
                    <td>
                        <a href="edit_class.php?id=<?php echo $cls['id']; ?>" class="btn-edit">Edit</a>
                        <a href="classes.php?action=delete&id=<?php echo $cls['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>