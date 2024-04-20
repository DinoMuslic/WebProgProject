<?php 
require_once __DIR__ . '/rest/services/ProfessorService.class.php';

$professor_id = $_REQUEST['id'];
if($professor_id == NULL || $professor_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'You have to provide valid professor ID']));
}

$professor_service = new ProfessorService();   
$professor_service -> delete_professor_by_id($professor_id);

echo json_encode(['message' => "You have succesfully deleted the professor"]);
