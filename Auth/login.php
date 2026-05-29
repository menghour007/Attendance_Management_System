<?php
// login/login.php
require_once '../db.php';
require_once '../Auth.php';

$database = new Db();
$db = $database->getConnection();
$auth = new Auth($db);

if (Auth::isLoggedIn()) {
    header("Location: ../dashboard/dashboard.php");
    exit;
}

$error_message = "";
$success_message = "";

// Check for the logout URL parameter
if (isset($_GET['msg']) && $_GET['msg'] === 'logout') {
    $success_message = "You have been logged out successfully.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $result = $auth->login($username, $password);
        
        if ($result === true) {
            header("Location: ../dashboard/dashboard.php");
            exit;
        } else {
            $error_message = $result; 
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Portal - Login</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #7f8c8d; font-size: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #cccccc; border-radius: 6px; box-sizing: border-box; font-size: 14px; transition: border 0.3s; }
        input[type="text"]:focus, input[type="password"]:focus { border-color: #3498db; outline: none; }
        button { width: 100%; padding: 12px; background-color: #3498db; border: none; color: white; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        button:hover { background-color: #2980b9; }
        
        /* Message Box Styles */
        .msg { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .error { color: #c0392b; background-color: #fde8e7; border: 1px solid #f5c6cb; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>School Portal</h2>
    
    <?php if (!empty($success_message)): ?>
        <div class="msg success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="msg error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Sign In</button>
    </form>
</div>

</body>
</html>