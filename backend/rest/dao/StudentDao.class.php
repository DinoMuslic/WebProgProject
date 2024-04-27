<?php 

require_once __DIR__ . "/BaseDao.class.php";

class StudentDao extends BaseDao {
    public function __construct() {
        parent::__construct('students');
    }

    public function add_student($student) {
        return $this->insert('students', $student);
    }

    public function count_students_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM students
                  WHERE LOWER(first_name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(faculty) LIKE CONCAT('%', :search, '%') OR
                        LOWER(department) LIKE CONCAT('%', :search, '%') OR
                        LOWER(enrolment_year) LIKE CONCAT('%', :search, '%');";
        
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_students_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $query = "SELECT * 
                  FROM students
                  WHERE LOWER(first_name) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(faculty) LIKE CONCAT('%', :search, '%') OR
                        LOWER(department) LIKE CONCAT('%', :search, '%') OR
                        LOWER(enrolment_year) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        
        return $this->query($query, [
            'search' => $search
        ]);
    }

    public function get_student_by_id($id) {
        return $this->query_unique("SELECT * FROM students WHERE id = :id", ['id' => $id]);
    }

    public function delete_student_by_id($id) {
        $query = "DELETE FROM students WHERE id = :id";
        $this->execute($query, ['id' => $id]);
    }

    public function edit_student($id, $student) {
        $query = "UPDATE students SET first_name = :first_name, last_name = :last_name, faculty = :faculty, department = :department, enrolment_year = :enrolment_year
                  WHERE id = :id";
        $this->execute($query, [
            'first_name' => $student['first_name'],
            'last_name' => $student['last_name'],
            'faculty' => $student['faculty'],
            'department' => $student['department'],
            'enrolment_year' => $student['enrolment_year'],
            'id' => $id
        ]);
    }
}