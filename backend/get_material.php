<?php 

require_once __DIR__ . '/rest/services/MaterialService.class.php';

$material_id = $_REQUEST['id'];

$material_service = new MaterialService();
$material = $material_service -> get_material_by_id($material_id);

header('Content-Type: application/json');
echo json_encode($material);