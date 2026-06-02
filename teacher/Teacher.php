<?php
// teacher/Teacher.php

class Teacher {
    private $db;
    private $table = "users";

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
    }

    // CREATE: Insert new user with role 'teacher'
    public function create($username, $password, $full_name, $is_active = 1) {
        // Double check username uniqueness first
        $checkQuery = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute([':username' => $username]);
        if ($checkStmt->rowCount() > 0) {
            return "Username already exists.";
        }

        $query = "INSERT INTO " . $this->table . " (username, password, full_name, role, is_active) 
                  VALUES (:username, :password, :full_name, 'teacher', :is_active)";
        
        $stmt = $this->db->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $result = $stmt->execute([
            ':username' => $username,
            ':password' => $hashed_password,
            ':full_name' => $full_name,
            ':is_active' => $is_active
        ]);

        return $result ? true : "Failed to create teacher account.";
    }

    // READ ALL: Fetch everyone who has a 'teacher' role
    public function getAll() {
        $query = "SELECT id, username, full_name, is_active, created_at 
                  FROM " . $this->table . " 
                  WHERE role = 'teacher' 
                  ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ SINGLE: Fetch data details for a specific teacher ID
    public function getById($id) {
        $query = "SELECT id, username, full_name, is_active 
                  FROM " . $this->table . " 
                  WHERE id = :id AND role = 'teacher' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE: Modify properties of an existing teacher record
    public function update($id, $username, $full_name, $is_active, $password = null) {
        if (!empty($password)) {
            // Update including a fresh password modification
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, full_name = :full_name, is_active = :is_active, password = :password 
                      WHERE id = :id AND role = 'teacher'";
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $params = [
                ':username' => $username,
                ':full_name' => $full_name,
                ':is_active' => $is_active,
                ':password' => $hashed_password,
                ':id' => $id
            ];
        } else {
            // Update excluding password fields
            $query = "UPDATE " . $this->table . " 
                      SET username = :username, full_name = :full_name, is_active = :is_active 
                      WHERE id = :id AND role = 'teacher'";
            $params = [
                ':username' => $username,
                ':full_name' => $full_name,
                ':is_active' => $is_active,
                ':id' => $id
            ];
        }

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    // DELETE: Remove record securely from user matrix table
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND role = 'teacher'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>