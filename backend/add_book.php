<?php 

require_once __DIR__ . '/rest/services/BookService.class.php';

$payload = $_REQUEST;

if($payload['title'] == NULL || $payload['title'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'First name field is missing']));
}

$book_service = new BookService();

if($payload['id'] != null && $payload['id'] != '') {
    $book = $book_service->edit_book($payload);
} else {
    unset($payload['id']);
    $book = $book_service->add_book($payload);
}

echo json_encode(['message' => "You have succesfully added the book", 'data' => $book]);