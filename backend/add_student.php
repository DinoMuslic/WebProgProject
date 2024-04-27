<?php 

require_once __DIR__ . '/rest/services/StudentService.class.php';

$payload = $_REQUEST;

if($payload['first_name'] == NULL || $payload['first_name'] == '' || $payload['last_name'] == NULL || $payload['last_name'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name or last name field is missing']));
}

$student_service = new StudentService();

if($payload['id'] != null && $payload['id'] != '') {
    $student = $student_service->edit_student($payload);
} else {
    unset($payload['id']);
    $student = $student_service->add_student($payload);
}

echo json_encode(['message' => "You have succesfully added the student", 'data' => $student]);