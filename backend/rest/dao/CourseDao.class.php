<?php 

require_once __DIR__ . "/BaseDao.class.php";

class CourseDao extends BaseDao {
    public function __construct() {
        parent::__construct('courses');
    }

    public function add_course($course) {
        return $this->insert('courses', $course);
    }

    public function count_courses_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM courses
                  WHERE LOWER(title) LIKE CONCAT('%', :search, '%');";
        
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_courses_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $query = "SELECT * 
                  FROM courses
                  WHERE LOWER(title) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        
        return $this->query($query, [
            'search' => $search
        ]);
    }
}