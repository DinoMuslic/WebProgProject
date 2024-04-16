<?php 
require_once __DIR__ . '/rest/services/CourseService.class.php';

$payload = $_REQUEST;


$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => $payload['order'][0]['name'],
    "order_direction" => $payload['order'][0]['dir'],
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



// Response
echo json_encode([
    'draw' => $params['draw'],
    'data' => $data['data'],
    'recordsFiltered' => $data['count'],
    'recordsTotal' => $data['count'],
    'end' => $data['count']
]);