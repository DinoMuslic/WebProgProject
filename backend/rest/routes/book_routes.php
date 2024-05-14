<?php
require_once __DIR__ . '/../services/BookService.class.php';

Flight::set('book_service', new BookService());

Flight::group('/books', function () {
    /**
     * @OA\Get(
     *      path="/books/all",
     *      tags={"books"},
     *      summary="Get all books",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all books in the databases"
     *      )
     * )
     */

    Flight::route('GET /all', function() {
        
        // Count query
        $data = Flight::get('book_service')->get_all_books();
    
        // Response
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/books/book",
     *      tags={"books"},
     *      summary="Get book by id",
     *      @OA\Response(
     *           response=200,
     *           description="book data or false if book doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="book ID")
     * )
     */

    Flight::route('GET /book', function() {
        $params = Flight::request()->query;
        $book = Flight::get('book_service')->get_book_by_id($params['id']);
        Flight::json($book, 200);
    });


    /**
     * @OA\Get(
     *      path="/books/get/{id}",
     *      tags={"books"},
     *      summary="Get book by id",
     *      @OA\Response(
     *           response=200,
     *           description="book data or false if book doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="book ID")
     * )
     */

    Flight::route('GET /get/@id', function($id) {
        $book = Flight::get('book_service')->get_book_by_id($id);
        Flight::json($book, 200);
    });

    Flight::route('GET /', function() {
        $payload = Flight::request()->query;
    
    
        $params = [
            "start" => (int)$payload['start'],
            "search" => $payload['search']['value'],
            "draw" => $payload['draw'],
            "limit" => (int)$payload['length'],
            "order_column" => isset($payload['order']) ? $payload['order'][0]['name'] : 'title',
            "order_direction" => isset($payload['order']) ? $payload['order'][0]['dir'] : 'asc',
    
        ];
    
    
        // Count query
        $data = Flight::get('book_service')->get_books_paginated(
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
        Flight::json([
            'draw' => $params['draw'],
            'data' => $data['data'],
            'recordsFiltered' => $data['count'],
            'recordsTotal' => $data['count'],
            'end' => $data['count']
        ], 200);
    });
    

    /**
     * @OA\Post(
     *      path="/books/add",
     *      tags={"books"},
     *      summary="Add book data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="book data, or exception if book is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="book data payload",
     *          @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="id", type="string", example="1", description="book ID"),
     *              @OA\Property(property="title", type="string", example="Some book title", description="book title"),
     *              @OA\Property(property="author", type="string", example="Some author", description="author name"),
     *              @OA\Property(property="genre", type="string", example="Some genre", description="book genre"),
     *              @OA\Property(property="publication_year", type="string", example="Some year", description="book publication year"),
     *              @OA\Property(property="pdf_link", type="string", example="Some book pdf link", description="book pdf link"),
     *          )
     *      )
     * )
     */

    Flight::route('POST /add', function() {
        $payload = Flight::request()->data->getData();
    
        if($payload['title'] == NULL || $payload['title'] == '') {
            Flight::halt(500, "First name field is missing!");
        }
    
        if($payload['id'] != null && $payload['id'] != '') {
            $book = Flight::get('book_service')->edit_book($payload);
        } else {
            unset($payload['id']);
            $book = Flight::get('book_service')->add_book($payload);
        }
    
        Flight::json(['message' => "You have succesfully added the book", 'data' => $book]);
    });

    /**
     * @OA\Delete(
     *      path="/books/delete/{id}",
     *      tags={"books"},
     *      summary="Delete book by id",
     *      @OA\Response(
     *           response=200,
     *           description="Delete book data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="book ID")
     * )
     */
    Flight::route('DELETE /delete/@book_id', function($book_id) {
        if($book_id == NULL || $book_id == '') {
            Flight::halt(500, "You have to provide valid book ID");
        }
       
        Flight::get('book_service') -> delete_book_by_id($book_id);
    
        Flight::json(['message' => "You have succesfully deleted the book"], 200);
    }); 
    
    /**
     * @OA\Get(
     *      path="/books/{id}",
     *      tags={"books"},
     *      summary="Get book by id",
     *      @OA\Response(
     *           response=200,
     *           description="book data or false if book doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="book ID")
     * )
     */
    
    Flight::route('GET /@book_id', function($book_id) {
        
        $book = Flight::get('book_service') -> get_book_by_id($book_id);
    
        Flight::json($book, 200);
    });
});



