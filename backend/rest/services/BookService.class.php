<?php 

require_once __DIR__ . '/../dao/BookDao.class.php';


class BookService {
    private $book_dao;

    public function __construct() {
        $this->book_dao = new BookDao();
    }

    public function add_book($book) {
        return $this->book_dao->add_book($book);
    }

    public function get_books_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $count = $this->book_dao->count_books_paginated($search)['count'];

        $rows =  $this->book_dao->get_books_paginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'count' => $count,
            'data' => $rows
        ];
    }

    public function delete_book_by_id($id) {
        return $this->book_dao->delete_book_by_id($id);
    }

    public function get_book_by_id($id) {
        return $this->book_dao->get_book_by_id($id);
    }

    public function edit_book($book) {
        $id = $book['id'];
        unset($book['id']);
        
        $this->book_dao->edit_book($id, $book);
    }
}