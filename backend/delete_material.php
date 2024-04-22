<?php 
require_once __DIR__ . '/rest/services/MaterialService.class.php';

$material_id = $_REQUEST['id'];
if($material_id == NULL || $material_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'You have to provide valid material ID']));
}

$material_service = new materialService();   
$material_service -> delete_material_by_id($material_id);

echo json_encode(['message' => "You have succesfully deleted the material"]);
