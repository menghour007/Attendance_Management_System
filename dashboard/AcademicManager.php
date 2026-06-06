<?php
// dashboard/AcademicManager.php

class AcademicManager {
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

    // ================= ACADEMIC YEARS =================
    public function getAcademicYearById($id) {
        $stmt = $this->db->prepare("SELECT * FROM academic_years WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAcademicYears() {
        $query = "SELECT * FROM academic_years ORDER BY year_label DESC, semester ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAcademicYear($year_label, $semester) {
        $query = "INSERT INTO academic_years (year_label, semester, is_current, is_open) VALUES (:label, :semester, 0, 1)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':label' => $year_label, ':semester' => $semester]);
    }
    
    public function updateAcademicYear($id, $year_label, $semester, $is_open) {
        $query = "UPDATE academic_years SET year_label = :label, semester = :sem, is_open = :open WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':label' => $year_label, ':sem' => $semester, ':open' => $is_open, ':id' => $id]);
    }

    // ================= CLASSES =================
    public function getClassById($id) {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getClasses() {
        // Joins the tables to get the readable names instead of just IDs
        $query = "SELECT c.id, c.section_name, cr.course_code, cr.course_name, ay.year_label, ay.semester 
                  FROM classes c 
                  JOIN courses cr ON c.course_id = cr.id 
                  JOIN academic_years ay ON c.year_id = ay.id 
                  ORDER BY ay.year_label DESC, cr.course_code ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addClass($course_id, $year_id, $section_name) {
        $query = "INSERT INTO classes (course_id, year_id, section_name) VALUES (:course_id, :year_id, :section_name)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':course_id' => $course_id, 
            ':year_id' => $year_id, 
            ':section_name' => $section_name
        ]);
    }

    public function updateClass($id, $course_id, $year_id, $section_name) {
        $query = "UPDATE classes SET course_id = :cid, year_id = :yid, section_name = :sec WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':cid' => $course_id, ':yid' => $year_id, ':sec' => $section_name, ':id' => $id]);
    }
}
?>