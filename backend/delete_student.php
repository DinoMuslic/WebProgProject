<?php 
require_once __DIR__ . '/rest/services/StudentService.class.php';

$student_id = $_REQUEST['id'];
if($student_id == NULL || $student_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'You have to provide valid student ID']));
}

$student_service = new StudentService();   
$student_service -> delete_student_by_id($student_id);

echo json_encode(['message' => "You have succesfully deleted the student"]);
