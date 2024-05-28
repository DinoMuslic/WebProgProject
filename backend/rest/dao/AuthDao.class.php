<?php

require_once __DIR__ . "/BaseDao.class.php";

class AuthDao extends BaseDao {
    public function __construct() {
        parent::__construct('students');
    }

    public function get_user_by_email($email) {
        // Query the students table first
        $query = "SELECT *, 'student' AS user_type
                  FROM students
                  WHERE email = :email";
        $student = $this->query_unique($query, ['email' => $email]);
        
        if ($student) {
            return $student;
        }

        // If not found in students, query the professors table
        $query = "SELECT *, 'professor' AS user_type
                  FROM professors
                  WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }
}

