<?php
// teacher/create.php
require_once '../Auth.php';
require_once '../db.php';
require_once 'Teacher.php';

// if (!Auth::isLoggedIn() || $_SESSION['role'] !== 'admin') {
//     header("Location: ../Auth/login.php");
//     exit;
// }

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $password  = trim($_POST['password']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($full_name) && !empty($username) && !empty($password)) {
        $database = new Db();
        $db = $database->getConnection();
        $teacherManager = new Teacher($db);

        $result = $teacherManager->create($username, $password, $full_name, $is_active);

        if ($result === true) {
            $success_message = "Teacher registered successfully!";
        } else {
            $error_message = $result;
        }
    } else {
        $error_message = "Please populate all mandatory fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Teacher</title>
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
    </style>
</head>

<body>

    <?php include_once '../dashboard/sidebar.php'; ?>

    <div class="main-content">
        <div class="card">
            <h1>Create New Teacher Account</h1>

            <?php if (!empty($error_message)): ?>
            <div class="msg msg-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
            <div class="msg msg-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <form action="create.php" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Account Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active" style="margin: 0; cursor: pointer;">Enable Account Status (Active)</label>
                </div>

                <button type="submit" class="btn-submit">Save Teacher Profile</button>
                <a href="index.php"
                    style="margin-left: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px;">Back to List</a>
            </form>
        </div>
    </div>

</body>

</html>