<?php 

require_once __DIR__ . '/rest/services/StudentService.class.php';

$student_id = $_REQUEST['id'];

$student_service = new studentService();
$student = $student_service -> get_student_by_id($student_id);

header('Content-Type: application/json');
echo json_encode($student);