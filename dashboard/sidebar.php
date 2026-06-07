<?php
// sidebar.php
// Ensure session is started to read roles
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_role = $_SESSION['role'] ?? '';

// Define the root folder URL path configuration dynamically or statically
$root = "/";
?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3>School Portal</h3>
    </div>

    <div class="user-info">
        <p class="user-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Guest'); ?></p>
        <span class="role-badge"><?php echo htmlspecialchars($current_role); ?></span>
    </div>

    <ul class="sidebar-menu">
        <li><a href="<?php echo $root; ?>dashboard/dashboard.php">🏠 Dashboard</a></li>

        <?php if ($current_role === 'admin'): ?>
            <li class="menu-header">Administration</li>
            <li><a href="<?php echo $root; ?>teacher/index.php">👥 Manage Teachers</a></li>
            <li><a href="<?php echo $root; ?>dashboard/manage_users.php">👥 Manage Users</a></li>
            <li><a href="<?php echo $root; ?>academic_years/academic_years.php">📅 Academic Years</a></li>
            <li><a href="<?php echo $root; ?>courses/courses.php">📚 Courses</a></li>
            <li><a href="<?php echo $root; ?>classes/classes.php">🏫 Classes</a></li>
        <?php endif; ?>

        <?php if ($current_role === 'teacher'): ?>
            <li class="menu-header">Teacher Workspace</li>
            <li><a href="<?php echo $root; ?>dashobard/schedules.php">📅 My Schedules</a></li>
            <li><a href="<?php echo $root; ?>dashboard/attendance.php">📝 Take Attendance</a></li>
            <li><a href="<?php echo $root; ?>dashboard/reports.php">📊 Attendance Reports</a></li>
        <?php endif; ?>

        <li class="menu-header">Account</li>
        <li><a href="<?php echo $root; ?>Auth/logout.php" class="logout-link">🚪 Logout</a></li>
    </ul>
</div>

<style>
    :root {
        --sidebar-width: 260px;
        --dark-bg: #2c3e50;
        --light-text: #ecf0f1;
        --accent-color: #3498db;
    }

    .sidebar {
        width: var(--sidebar-width);
        background-color: var(--dark-bg);
        color: var(--light-text);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar-brand {
        padding: 20px;
        text-align: center;
        background: #1a252f;
        border-bottom: 1px solid #34495e;
    }

    .sidebar-brand h3 {
        margin: 0;
        font-weight: 600;
        color: #fff;
    }

    .user-info {
        padding: 20px;
        text-align: center;
        background: #243342;
        border-bottom: 1px solid #34495e;
    }

    .user-name {
        margin: 0 0 5px 0;
        font-weight: bold;
        font-size: 15px;
    }

    .role-badge {
        display: inline-block;
        padding: 3px 10px;
        background: #e67e22;
        color: white;
        border-radius: 12px;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-header {
        padding: 15px 20px 5px 20px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #7f8c8d;
        font-weight: bold;
    }

    .sidebar-menu li a {
        display: block;
        padding: 12px 20px;
        color: #bdc3c7;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
    }

    .sidebar-menu li a:hover {
        background: #34495e;
        color: #fff;
        padding-left: 25px;
    }

    .sidebar-menu li a.active {
        background: var(--accent-color);
        color: white;
        font-weight: bold;
    }

    .sidebar-menu li a.logout-link:hover {
        background: #c0392b;
    }
</style>