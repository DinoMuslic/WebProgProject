<?php 

require_once __DIR__ . '/../dao/MaterialDao.class.php';


class MaterialService {
    private $material_dao;

    public function __construct() {
        $this->material_dao = new MaterialDao();
    }

    public function add_material($material) {
        return $this->material_dao->add_material($material);
    }

    public function get_materials_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $count = $this->material_dao->count_materials_paginated($search)['count'];

        $rows =  $this->material_dao->get_materials_paginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'count' => $count,
            'data' => $rows
        ];
    }

    public function delete_material_by_id($id) {
        return $this->material_dao->delete_material_by_id($id);
    }

    public function delete_materials_by_course_id($course_id) {
        return $this->material_dao->delete_materials_by_course_id($course_id);
    }

    public function get_material_by_course_id($course_id) {
        return $this->material_dao->get_material_by_course_id($course_id);
    }

    public function get_all_materials_by_course_id($course_id) {
        return $this->material_dao->get_all_materials_by_course_id($course_id);
    }

    public function get_material_by_id($id) {
        return $this->material_dao->get_material_by_id($id);
    }

    public function edit_material($material) {
        $id = $material['id'];
        unset($material['id']);
        
        $this->material_dao->edit_material($id, $material);
    }

    public function get_all_materials() {
        return $this->material_dao->get_all_materials();
    }
}