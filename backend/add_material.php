<?php 

require_once __DIR__ . '/rest/services/MaterialService.class.php';

$payload = $_REQUEST;

if($payload['title'] == NULL || $payload['title'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name field is missing']));
}

$material_service = new MaterialService();

if($payload['id'] != null && $payload['id'] != '') {
    $material = $material_service->edit_material($payload);
} else {
    unset($payload['id']);
    $material = $material_service->add_material($payload);
}

echo json_encode(['message' => "You have succesfully added the material", 'data' => $material]);