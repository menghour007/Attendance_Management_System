<?php
require_once 'Database.php';

// Create database object
$db = new Database();

// Get last student code for placeholder
$lastStudentCode = $db->getLastStudentCode();

// Handle form submission
if(isset($_POST['submit'])) {
    $student_code = $_POST['student_code'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $class_id = $_POST['class_id'];
    $enrollment_date = date('Y-m-d');
    $status = 'active';
    
    // Check if student code exists
    if($db->checkStudentCode($student_code)) {
        $error = "Student code already exists!";
    } else {
        // Insert student
        $student_id = $db->insertStudent($student_code, $first_name, $last_name);
        
        if($student_id) {
            // Insert enrollment
            if($db->insertEnrollment($student_id, $class_id, $enrollment_date, $status)) {
                $success = "Student enrolled successfully!";
                // Refresh last student code
                $lastStudentCode = $db->getLastStudentCode();
            } else {
                $error = "Enrollment failed!";
            }
        } else {
            $error = "Failed to add student!";
        }
    }
}

// Get classes for dropdown (returns array from PDO)
$classes = $db->getClasses();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration & Enrollment</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f0f2f5;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 14px;
            color: #ecf0f1;
        }
        
        .form-section {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 3px rgba(44,62,80,0.3);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button:hover {
            background: #34495e;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .info-box {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 4px;
            margin-top: 25px;
            border-left: 4px solid #2c3e50;
        }
        
        .info-box h4 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .info-box ul {
            list-style: none;
            padding-left: 0;
        }
        
        .info-box li {
            padding: 5px 0;
            color: #333;
            font-size: 13px;
        }
        
        .last-code {
            background: #2c3e50;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            font-size: 12px;
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .form-section {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Student Registration & Enrollment</h1>
            <p>Fill in the form below to register a new student</p>
        </div>
        
        <div class="form-section">
            <?php if(isset($success)): ?>
                <div class="alert-success">✓ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="alert-error">✗ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Student Code:</label>
                    <input type="text" name="student_code" 
                           placeholder="Last used: <?php echo $lastStudentCode; ?> (e.g., STU001)" 
                           required>
                </div>
                
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="first_name" placeholder="Enter first name" required>
                </div>
                
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="last_name" placeholder="Enter last name" required>
                </div>
                
                <div class="form-group">
                    <label>Select Class:</label>
                    <select name="class_id" required>
                        <option value="">-- Select Class --</option>
                        <?php foreach($classes as $row): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['course_code'] . " - " . $row['course_name'] . " (Section: " . $row['section_name'] . ", " . $row['year_label'] . " Semester " . $row['semester'] . ")"; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="submit">Register & Enroll Student</button>
            </form>
            
            <div class="info-box">
                <h4>Information:</h4>
                <ul>
                    <li>✓ Student will be added to students table</li>
                    <li>✓ Enrollment will be added with status "active"</li>
                    <li>✓ Enrollment date will be set to today's date</li>
                </ul>
                <div class="last-code">
                    Last student code: <?php echo $lastStudentCode; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
$db->closeConnection();
?>