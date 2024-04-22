<?php 
require_once __DIR__ . '/rest/services/BookService.class.php';

$book_id = $_REQUEST['id'];
if($book_id == NULL || $book_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => 'You have to provide valid book ID']));
}

$book_service = new BookService();   
$book_service -> delete_book_by_id($book_id);

echo json_encode(['message' => "You have succesfully deleted the book"]);
