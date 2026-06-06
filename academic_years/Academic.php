<?php

class Academic {
    private $db;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
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

    public function deleteAcademicYear($id) {
        $stmt = $this->db->prepare("DELETE FROM academic_years WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>