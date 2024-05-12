<?php 

require_once __DIR__ . '/../dao/CourseDao.class.php';


class CourseService {
    private $course_dao;

    public function __construct() {
        $this->course_dao = new CourseDao();
    }

    public function add_course($course) {
        return $this->course_dao->add_course($course);
    }

    public function get_courses_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $count = $this->course_dao->count_courses_paginated($search)['count'];

        $rows =  $this->course_dao->get_courses_paginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'count' => $count,
            'data' => $rows
        ];
    }

    public function delete_course_by_id($id) {
        return $this->course_dao->delete_course_by_id($id);
    }

    public function get_course_by_id($id) {
        return $this->course_dao->get_course_by_id($id);
    }

    public function edit_course($course) {
        $id = $course['id'];
        unset($course['id']);
        
        $this->course_dao->edit_course($id, $course);
    }

    public function get_all_courses() {
        return $this->course_dao->get_all_courses();
    }
}