<?php 

require_once __DIR__ . '/rest/services/CourseService.class.php';



$payload = $_REQUEST;

if($payload['title'] == NULL || $payload['title'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name field is missing']));
}

$course_service = new CourseService();
$course = $course_service->add_course($payload);

echo json_encode(['message' => "You have succesfully added the course", 'data' => $course]);