<?php 
require_once __DIR__ . '/rest/services/MaterialService.class.php';

$payload = $_REQUEST;


$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => isset($payload['order']) ? $payload['order'][0]['name'] : 'title',
    "order_direction" => isset($payload['order']) ? $payload['order'][0]['dir'] : 'asc',

];

$material_service = new MaterialService();

// Count query
$data = $material_service->get_materials_paginated(
    $params['start'],
    $params['limit'],
    $params['search'],
    $params['order_column'],
    $params['order_direction']
);

foreach($data['data'] as $id => $material) {
    $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                        '<div class="btn-group" role="group" aria-label="Actions">' .
                                            '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="MaterialService.open_edit_material_modal('. $material['id'] .')">Edit</button>' .
                                            '<button type="button" class="btn btn-outline-danger" onClick="MaterialService.delete_material('. $material['id'] .')">Delete</button>' .
                                        '</div>' . 
                                    '</div>';    
}

// Response
echo json_encode([
    'draw' => $params['draw'],
    'data' => $data['data'],
    'recordsFiltered' => $data['count'],
    'recordsTotal' => $data['count'],
    'end' => $data['count']
]);