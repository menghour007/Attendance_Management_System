<?php

class Course {
    private $db;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
    }

    // ================= COURSES =================
    public function getCourseById($id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCourses() {
        $query = "SELECT * FROM courses ORDER BY course_code";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourse($course_code, $course_name) {
        $query = "INSERT INTO courses (course_code, course_name) VALUES (:code, :name)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':code' => $course_code, ':name' => $course_name]);
    }
    
    public function updateCourse($id, $course_code, $course_name) {
        $query = "UPDATE courses SET course_code = :code, course_name = :name WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':code' => $course_code, ':name' => $course_name, ':id' => $id]);
    }

    public function deleteCourse($id) {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>