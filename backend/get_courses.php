<?php 
require_once __DIR__ . '/rest/services/CourseService.class.php';

$payload = $_REQUEST;


$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => isset($payload['order']) ? $payload['order'][0]['name'] : 'title',
    "order_direction" => isset($payload['order']) ? $payload['order'][0]['dir'] : 'asc',

];

$course_service = new CourseService();

// Count query
$data = $course_service->get_courses_paginated(
    $params['start'],
    $params['limit'],
    $params['search'],
    $params['order_column'],
    $params['order_direction']
);

foreach($data['data'] as $id => $course) {
    $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                        '<div class="btn-group" role="group" aria-label="Actions">' .
                                            '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="CourseService.open_edit_course_modal('. $course['id'] .')">Edit</button>' .
                                            '<button type="button" class="btn btn-outline-danger" onClick="CourseService.delete_course('. $course['id'] .')">Delete</button>' .
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