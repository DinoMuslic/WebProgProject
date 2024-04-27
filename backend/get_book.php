<?php 

require_once __DIR__ . '/rest/services/BookService.class.php';

$book_id = $_REQUEST['id'];

$book_service = new BookService();
$book = $book_service -> get_book_by_id($book_id);

header('Content-Type: application/json');
echo json_encode($book);