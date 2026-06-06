<?php
// academic_years/academic_years.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Academic.php';

if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') { header("Location: ../Auth/login.php"); exit; }

$database = new Db();
$manager = new Academic($database->getConnection());
$message = "";

// --- DELETE LOGIC ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($manager->deleteAcademicYear($_GET['id'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Academic Year deleted!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Failed to delete academic year.</div>";
    }
}

// --- CREATE LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_year'])) {
    if ($manager->addAcademicYear($_POST['year_label'], $_POST['semester'])) {
        $message = "<div style='color: green; margin-bottom: 15px;'>Academic Year added!</div>";
    } else {
        $message = "<div style='color: red; margin-bottom: 15px;'>Error adding academic year.</div>";
    }
}
$years = $manager->getAcademicYears();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Academic Years</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px;}
        input[type="text"], select { padding: 8px; margin-right: 10px; width: 200px;}
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
            <h2>Add Academic Year</h2>
            <?php echo $message; ?>
            <form method="POST" action="academic_years.php">
                <input type="text" name="year_label" placeholder="Label (e.g., 2023-2024)" required>
                <select name="semester" required>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
                <button type="submit" name="add_year">Save Academic Year</button>
            </form>
        </div>

        <div class="card">
            <h2>Existing Academic Years</h2>
            <table>
                <tr><th>ID</th><th>Year Label</th><th>Semester</th><th>Status</th><th>Actions</th></tr>
                <?php foreach ($years as $yr): ?>
                <tr>
                    <td><?php echo $yr['id']; ?></td>
                    <td><?php echo htmlspecialchars($yr['year_label']); ?></td>
                    <td>Semester <?php echo htmlspecialchars($yr['semester']); ?></td>
                    <td><?php echo $yr['is_open'] ? '<span style="color: green;">Open</span>' : '<span style="color: red;">Closed</span>'; ?></td>
                    <td>
                        <a href="edit_year.php?id=<?php echo $yr['id']; ?>" class="btn-edit">Edit</a>
                        <a href="academic_years.php?action=delete&id=<?php echo $yr['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this Academic Year?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>