<?php
require_once '../db.php';

class Database {
    private $conn;
    
    // Constructor - use existing PDO connection
    public function __construct() {
        $db = new Db();
        $this->conn = $db->getConnection();
    }
    
    // Get connection
    public function getConnection() {
        return $this->conn;
    }
    
    // ============ STUDENT METHODS ============
    
    // Get last student code
    public function getLastStudentCode() {
        $sql = "SELECT student_code FROM students ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            return $row['student_code'];
        }
        return "STU000";
    }
    
    // Insert student
    public function insertStudent($student_code, $first_name, $last_name) {
        $sql = "INSERT INTO students (student_code, first_name, last_name) 
                VALUES (:student_code, :first_name, :last_name)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_code', $student_code);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Check if student code exists
    public function checkStudentCode($student_code) {
        $sql = "SELECT * FROM students WHERE student_code = :student_code";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_code', $student_code);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Get all students
    public function getAllStudents() {
        $sql = "SELECT * FROM students ORDER BY id DESC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get single student by ID
    public function getStudentById($id) {
        $sql = "SELECT * FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // ============ COURSE METHODS ============
    
    // Get all courses
    public function getCourses() {
        $sql = "SELECT * FROM courses ORDER BY course_code";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Insert course
    public function insertCourse($course_code, $course_name) {
        $sql = "INSERT INTO courses (course_code, course_name) 
                VALUES (:course_code, :course_name)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':course_name', $course_name);
        
        return $stmt->execute();
    }
    
    // ============ ACADEMIC YEAR METHODS ============
    
    // Get all academic years
    public function getAcademicYears() {
        $sql = "SELECT * FROM academic_years ORDER BY year_label DESC, semester";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get open academic years
    public function getOpenAcademicYears() {
        $sql = "SELECT * FROM academic_years WHERE is_open = 1 ORDER BY year_label DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // ============ CLASS METHODS ============
    
    // Get all classes for dropdown (with course and year info)
    public function getClasses() {
        $sql = "SELECT c.*, cr.course_name, cr.course_code, ay.year_label, ay.semester 
                FROM classes c 
                JOIN courses cr ON c.course_id = cr.id 
                JOIN academic_years ay ON c.year_id = ay.id
                WHERE ay.is_open = 1
                ORDER BY ay.year_label DESC, cr.course_code";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get all classes (without filter)
    public function getAllClasses() {
        $sql = "SELECT c.*, cr.course_name, cr.course_code, ay.year_label, ay.semester 
                FROM classes c 
                JOIN courses cr ON c.course_id = cr.id 
                JOIN academic_years ay ON c.year_id = ay.id
                ORDER BY ay.year_label DESC, cr.course_code";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Insert class
    public function insertClass($course_id, $year_id, $section_name) {
        $sql = "INSERT INTO classes (course_id, year_id, section_name) 
                VALUES (:course_id, :year_id, :section_name)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':year_id', $year_id);
        $stmt->bindParam(':section_name', $section_name);
        
        return $stmt->execute();
    }
    
    // ============ ENROLLMENT METHODS ============
    
    // Insert enrollment
    public function insertEnrollment($student_id, $class_id, $enrollment_date, $status) {
        $sql = "INSERT INTO enrollments (student_id, class_id, enrollment_date, status) 
                VALUES (:student_id, :class_id, :enrollment_date, :status)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':enrollment_date', $enrollment_date);
        $stmt->bindParam(':status', $status);
        
        return $stmt->execute();
    }
    
    // Get enrollments with student and class info
    public function getEnrollments() {
        $sql = "SELECT e.*, s.student_code, s.first_name, s.last_name, 
                       cr.course_code, cr.course_name, c.section_name, ay.year_label, ay.semester
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN classes c ON e.class_id = c.id
                JOIN courses cr ON c.course_id = cr.id
                JOIN academic_years ay ON c.year_id = ay.id
                ORDER BY e.id DESC LIMIT 20";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get enrollments by student
    public function getEnrollmentsByStudent($student_id) {
        $sql = "SELECT e.*, cr.course_code, cr.course_name, c.section_name, ay.year_label
                FROM enrollments e
                JOIN classes c ON e.class_id = c.id
                JOIN courses cr ON c.course_id = cr.id
                JOIN academic_years ay ON c.year_id = ay.id
                WHERE e.student_id = :student_id
                ORDER BY e.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Update enrollment status
    public function updateEnrollmentStatus($enrollment_id, $status) {
        $sql = "UPDATE enrollments SET status = :status WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $enrollment_id);
        
        return $stmt->execute();
    }
    
    // ============ USER METHODS ============
    
    // Get all teachers
    public function getTeachers() {
        $sql = "SELECT * FROM users WHERE role = 'teacher' AND is_active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get all users
    public function getUsers() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Insert user
    public function insertUser($username, $password, $full_name, $role) {
        $hashed_password = md5($password);
        $sql = "INSERT INTO users (username, password, full_name, role) 
                VALUES (:username, :password, :full_name, :role)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':role', $role);
        
        return $stmt->execute();
    }
    
    // Check if username exists
    public function checkUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // ============ SCHEDULE METHODS ============
    
    // Get schedules by class
    public function getSchedulesByClass($class_id) {
        $sql = "SELECT s.*, u.full_name as teacher_name, c.section_name, cr.course_name
                FROM schedules s
                JOIN users u ON s.teacher_id = u.id
                JOIN classes c ON s.class_id = c.id
                JOIN courses cr ON c.course_id = cr.id
                WHERE s.class_id = :class_id
                ORDER BY FIELD(s.day_of_week, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'), s.start_time";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Insert schedule
    public function insertSchedule($class_id, $teacher_id, $day_of_week, $start_time, $end_time, $room_number) {
        $sql = "INSERT INTO schedules (class_id, teacher_id, day_of_week, start_time, end_time, room_number) 
                VALUES (:class_id, :teacher_id, :day_of_week, :start_time, :end_time, :room_number)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':day_of_week', $day_of_week);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':room_number', $room_number);
        
        return $stmt->execute();
    }
    
    // ============ ATTENDANCE METHODS ============
    
    // Insert attendance record
    public function insertAttendance($schedule_id, $student_id, $attendance_date, $status, $remark) {
        $sql = "INSERT INTO attendance_records (schedule_id, student_id, attendance_date, status, remark) 
                VALUES (:schedule_id, :student_id, :attendance_date, :status, :remark)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':schedule_id', $schedule_id);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':attendance_date', $attendance_date);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':remark', $remark);
        
        return $stmt->execute();
    }
    
    // Get attendance by schedule and date
    public function getAttendanceByScheduleAndDate($schedule_id, $attendance_date) {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.student_code
                FROM attendance_records a
                JOIN students s ON a.student_id = s.id
                WHERE a.schedule_id = :schedule_id AND a.attendance_date = :attendance_date";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':schedule_id', $schedule_id);
        $stmt->bindParam(':attendance_date', $attendance_date);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get attendance by student
    public function getAttendanceByStudent($student_id) {
        $sql = "SELECT a.*, cr.course_name, c.section_name, s.day_of_week, s.start_time
                FROM attendance_records a
                JOIN schedules s ON a.schedule_id = s.id
                JOIN classes c ON s.class_id = c.id
                JOIN courses cr ON c.course_id = cr.id
                WHERE a.student_id = :student_id
                ORDER BY a.attendance_date DESC LIMIT 20";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // ============ GENERAL METHODS ============
    
    // Close connection (PDO doesn't need explicit close, but method for compatibility)
    public function closeConnection() {
        $this->conn = null;
    }
}
?>