<?php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Teacher.php';

if (!Auth::isLoggedIn()) {
    header("Location: /Auth/login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: /dashboard/dashboard.php");
    exit;
}

$database = new Db();
$db = $database->getConnection();
$teacherManager = new Teacher($db);

$error_message = "";
$success_message = "";

// Fetch user entry from string parameter parameters query
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$teacherData = $teacherManager->getById($id);

if (!$teacherData) {
    die("Teacher profile entry records not found or match schema targets.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $password  = trim($_POST['password']); // Blank ignores updates
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($full_name) && !empty($username)) {
        $updateResult = $teacherManager->update($id, $username, $full_name, $is_active, $password);
        
        if ($updateResult) {
            $success_message = "Teacher adjustments successfully recorded!";
            // Refresh variables array display targets data 
            $teacherData = $teacherManager->getById($id);
        } else {
            $error_message = "Database execution anomaly mapping error occurred.";
        }
    } else {
        $error_message = "Full Name and Username cannot be empty fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Teacher</title>
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        background-color: #f4f6f9;
    }

    .main-content {
        margin-left: 260px;
        padding: 40px;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        max-width: 600px;
    }

    h1 {
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        color: #34495e;
        font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-submit {
        background: #3498db;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        font-size: 15px;
    }

    .btn-submit:hover {
        background: #2980b9;
    }

    .msg {
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .msg-error {
        color: #721c24;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .msg-success {
        color: #155724;
        background: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .hint {
        color: #7f8c8d;
        font-size: 12px;
        font-weight: normal;
        margin-top: 4px;
        display: block;
    }
    </style>
</head>

<body>

    <?php include_once '../dashboard/sidebar.php'; ?>

    <div class="main-content">
        <div class="card">
            <h1>Modify Teacher Account</h1>

            <?php if(!empty($error_message)): ?>
            <div class="msg msg-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if(!empty($success_message)): ?>
            <div class="msg msg-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name"
                        value="<?php echo htmlspecialchars($teacherData['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($teacherData['username']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label>Account Password</label>
                    <input type="password" name="password">
                    <span class="hint">Leave this field blank to retain the current password securely.</span>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        <?php echo $teacherData['is_active'] == 1 ? 'checked' : ''; ?>>
                    <label for="is_active" style="margin: 0; cursor: pointer;">Account Status (Active)</label>
                </div>

                <button type="submit" class="btn-submit">Update Teacher Details</button>
                <a href="index.php"
                    style="margin-left: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px;">Cancel & Go
                    Back</a>
            </form>
        </div>
    </div>

</body>

</html>