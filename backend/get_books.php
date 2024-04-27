<?php 
require_once __DIR__ . '/rest/services/BookService.class.php';

$payload = $_REQUEST;


$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => isset($payload['order']) ? $payload['order'][0]['name'] : 'title',
    "order_direction" => isset($payload['order']) ? $payload['order'][0]['dir'] : 'asc',

];

$book_service = new BookService();

// Count query
$data = $book_service->get_books_paginated(
    $params['start'],
    $params['limit'],
    $params['search'],
    $params['order_column'],
    $params['order_direction']
);

foreach($data['data'] as $id => $book) {
    $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                        '<div class="btn-group" role="group" aria-label="Actions">' .
                                            '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="BookService.open_edit_book_modal('. $book['id'] .')">Edit</button>' .
                                            '<button type="button" class="btn btn-outline-danger" onClick="BookService.delete_book('. $book['id'] .')">Delete</button>' .
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