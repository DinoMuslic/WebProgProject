<?php 
require_once __DIR__ . '/rest/services/StudentService.class.php';

$payload = $_REQUEST;


$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => isset($payload['order']) ? $payload['order'][0]['name'] : 'first_name',
    "order_direction" => isset($payload['order']) ? $payload['order'][0]['dir'] : 'asc',

];

$student_service = new StudentService();

// Count query
$data = $student_service->get_students_paginated(
    $params['start'],
    $params['limit'],
    $params['search'],
    $params['order_column'],
    $params['order_direction']
);

foreach($data['data'] as $id => $student) {
    $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                        '<div class="btn-group" role="group" aria-label="Actions">' .
                                            '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="StudentService.open_edit_student_modal('. $student['id'] .')">Edit</button>' .
                                            '<button type="button" class="btn btn-outline-danger" onClick="StudentService.delete_student('. $student['id'] .')">Delete</button>' .
                                        '</div>' . 
                                    '</div>';    
}

// Response
echo json_encode([
    'draw' => $params['draw'],
    'data' => $data['data'],
    'recordsFiltered' => $data['count'],
    'recordsTotal' => $data['count'],
    'end' => $data['count']
]);