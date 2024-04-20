<?php 

require_once __DIR__ . '/rest/services/ProfessorService.class.php';

$payload = $_REQUEST;

if($payload['first_name'] == NULL || $payload['first_name'] == '' || $payload['last_name'] == NULL || $payload['last_name'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name or last name field is missing']));
}

$professor_service = new ProfessorService();

if($payload['id'] != null && $payload['id'] != '') {
    $professor = $professor_service->edit_professor($payload);
} else {
    unset($payload['id']);
    $professor = $professor_service->add_professor($payload);
}

echo json_encode(['message' => "You have succesfully added the professor", 'data' => $professor]);