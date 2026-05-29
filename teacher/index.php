<?php
// teacher/index.php
require_once '../login/Auth.php';
require_once '../db.php';
require_once 'Teacher.php';

// Route restriction guard: Ensure user is logged in as an admin
if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

$database = new Db();
$db = $database->getConnection();
$teacherManager = new Teacher($db);

// Process delete action if clicked
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $teacherManager->delete($_GET['id']);
    header("Location: index.php");
    exit;
}

$teachers = $teacherManager->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background-color: #f4f6f9; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { color: #2c3e50; margin: 0; }
        .btn { padding: 10px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; }
        .btn-add { background: #2ecc71; color: white; }
        .btn-edit { background: #3498db; color: white; margin-right: 5px; padding: 5px 10px; font-size: 12px; }
        .btn-delete { background: #e74c3c; color: white; padding: 5px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eaeded; }
        th { background-color: #f8f9fa; color: #34495e; font-weight: 600; }
        .status { padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <?php include_once '../dashboard/sidebar.php'; ?>

    <div class="main-content">
        <div class="card">
            <div class="header-box">
                <h1>Teachers Directory Management</h1>
                <a href="create.php" class="btn btn-add">➕ Add New Teacher</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($teachers) > 0): ?>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($teacher['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($teacher['full_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($teacher['username']); ?></td>
                                <td>
                                    <span class="status <?php echo $teacher['is_active'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $teacher['is_active'] == 1 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($teacher['created_at']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $teacher['id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=delete&id=<?php echo $teacher['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to completely remove this teacher account profile?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #7f8c8d;">No teacher profiles found in system.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>