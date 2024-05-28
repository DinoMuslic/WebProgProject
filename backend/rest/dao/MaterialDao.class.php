<?php 

require_once __DIR__ . "/BaseDao.class.php";

class MaterialDao extends BaseDao {
    public function __construct() {
        parent::__construct('course_materials');
    }

    public function add_material($material) {
        return $this->insert('course_materials', $material);
    }

    public function count_materials_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM course_materials
                  WHERE LOWER(course_id) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(title) LIKE CONCAT('%', :search, '%') OR
                        LOWER(announcements) LIKE CONCAT('%', :search, '%') OR
                        LOWER(contents) LIKE CONCAT('%', :search, '%');"; 
        
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_materials_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $query = "SELECT * 
                  FROM course_materials
                  WHERE LOWER(course_id) LIKE CONCAT('%', :search, '%') OR
                        LOWER(title) LIKE CONCAT('%', :search, '%') OR
                        LOWER(announcements) LIKE CONCAT('%', :search, '%') OR
                        LOWER(contents) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        
        return $this->query($query, [
            'search' => $search
        ]);
    }

    public function get_material_by_id($id) {
        return $this->query_unique("SELECT * FROM course_materials WHERE id = :id", ['id' => $id]);
    }

    public function get_material_by_course_id($course_id) {
        return $this->query_unique("SELECT * FROM course_materials WHERE course_id = :course_id", ['course_id' => $course_id]);
    }

    public function get_all_materials_by_course_id($course_id) {
        return $this->query("SELECT * FROM course_materials WHERE course_id = :course_id", ['course_id' => $course_id]);
    }

    public function delete_material_by_id($id) {
        $query = "DELETE FROM course_materials WHERE id = :id";
        $this->execute($query, ['id' => $id]);
    }

    public function delete_materials_by_course_id($course_id) {
        $query = "DELETE FROM course_materials WHERE course_id = :course_id";
        $this->execute($query, ['course_id' => $course_id]);
    }

    public function edit_material($id, $material) {
        $query = "UPDATE course_materials SET course_id = :course_id, title = :title, announcements = :announcements, contents = :contents
                  WHERE id = :id";
        $this->execute($query, [
            'course_id' => $material['course_id'],
            'title' => $material['title'],
            'announcements' => $material['announcements'],
            'contents' => $material['contents'],
            'id' => $id
        ]);
    }

    public function get_all_materials() {
        $query = "SELECT * FROM course_materials";
        return  $this->query($query, []);
    }
    
}