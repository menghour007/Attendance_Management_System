<?php
// Auth.php

class Auth {
    private $db;
    private $table = "users";

    // --- Users Table Properties ---
    public $id;
    public $username;
    public $password; 
    public $full_name;
    public $role;     // 'admin' or 'teacher'
    public $is_active;
    public $created_at;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        $query = "SELECT id, username, password, full_name, role, is_active, created_at 
                  FROM " . $this->table . " 
                  WHERE username = :username LIMIT 1";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            // Check if user is active
            if ($user['is_active'] != 1) {
                return "Your account is deactivated. Please contact an admin.";
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                
                // Populate class properties
                $this->id         = $user['id'];
                $this->username   = $user['username'];
                $this->full_name  = $user['full_name'];
                $this->role       = $user['role'];
                $this->is_active  = $user['is_active'];
                $this->created_at = $user['created_at'];

                // Save critical elements to session store
                $_SESSION['user_id']   = $this->id;
                $_SESSION['username']  = $this->username;
                $_SESSION['full_name'] = $this->full_name;
                $_SESSION['role']      = $this->role;
                
                return true; 
            }
        }
        return "Invalid username or password.";
    }

    public static function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array(); 
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>