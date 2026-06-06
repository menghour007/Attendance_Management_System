<?php

class ClassManager {
    private $db;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
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

    public function deleteClass($id) {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>