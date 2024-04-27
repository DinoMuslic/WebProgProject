<?php 

require_once __DIR__ . '/rest/services/ProfessorService.class.php';

$professor_id = $_REQUEST['id'];

$professor_service = new ProfessorService();
$professor = $professor_service -> get_professor_by_id($professor_id);

header('Content-Type: application/json');
echo json_encode($professor);