<?php
// academic_years/edit_year.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Academic.php';

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') { header("Location: ../Auth/login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: academic_years.php"); exit; }
$id = $_GET['id'];

$database = new Db();
$manager = new Academic($database->getConnection());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_open = isset($_POST['is_open']) ? 1 : 0;
    if ($manager->updateAcademicYear($id, $_POST['year_label'], $_POST['semester'], $is_open)) {
        header("Location: academic_years.php"); exit;
    }
}
$year = $manager->getAcademicYearById($id);
if (!$year) { die("Academic Year not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Academic Year</title>
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
            <h2>Edit Academic Year</h2>
            <form method="POST">
                <label>Year Label</label>
                <input type="text" name="year_label" value="<?php echo htmlspecialchars($year['year_label']); ?>" required>
                
                <label>Semester</label>
                <select name="semester" required>
                    <option value="1" <?php if($year['semester'] == '1') echo 'selected'; ?>>Semester 1</option>
                    <option value="2" <?php if($year['semester'] == '2') echo 'selected'; ?>>Semester 2</option>
                </select>

                <div style="margin-bottom: 20px;">
                    <input type="checkbox" name="is_open" id="is_open" value="1" <?php if($year['is_open'] == 1) echo 'checked'; ?> style="width: auto;">
                    <label for="is_open" style="cursor:pointer;">Open for Enrollment?</label>
                </div>
                
                <button type="submit">Update Year</button>
                <a href="academic_years.php" style="margin-left: 15px; color: #7f8c8d; text-decoration: none;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>