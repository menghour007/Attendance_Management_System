<?php
// dashboard.php
require_once '../Auth.php';

// Route back to sign-in page if session context does not exist
if (!Auth::isLoggedIn()) {
    header("Location: ../Auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Attendance Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        .welcome-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        h1 { color: #2c3e50; margin-top: 0; }
        p { color: #555; line-height: 1.6; }
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            border-left: 4px solid #3498db;
        }
        .stat-box h3 { margin: 0 0 10px 0; color: #7f8c8d; font-size: 14px; text-transform: uppercase; }
        .stat-box p { margin: 0; font-size: 24px; font-weight: bold; color: #2c3e50; }
    </style>
</head>
<body>

    <?php include_once 'sidebar.php'; ?>

    <div class="main-content">
        <div class="welcome-card">
            <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
            <p>You are managing the portal environment using authorization tier mapping rules.</p>
            
            <hr style="border:0; border-top: 1px solid #eaeded; margin: 20px 0;">

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <h2>🛡️ System Administrator Console</h2>
                <p>Use the structural navigation options on your left sidebar map to create or deactivate teacher records, configure active academic periods, or review systemic logging configurations.</p>
                
                <div class="grid-stats">
                    <div class="stat-box" style="border-left-color: #2ecc71;">
                        <h3>Active Teachers</h3>
                        <p>12 Staff Members</p>
                    </div>
                    <div class="stat-box" style="border-left-color: #9b59b6;">
                        <h3>Total Active Classes</h3>
                        <p>24 Sections</p>
                    </div>
                </div>

            <?php elseif ($_SESSION['role'] === 'teacher'): ?>
                <h2>👨‍🏫 Instructor Action Terminal</h2>
                <p>Access your designated schedule boards via the workspace links to register daily student rosters, mark session late arrivals, or print historical reports.</p>
                
                <div class="grid-stats">
                    <div class="stat-box" style="border-left-color: #f1c40f;">
                        <h3>Today's Classes</h3>
                        <p>3 Sessions</p>
                    </div>
                    <div class="stat-box" style="border-left-color: #e74c3c;">
                        <h3>Pending Rosters</h3>
                        <p>1 Class Remaining</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>