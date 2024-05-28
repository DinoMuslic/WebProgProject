<?php
require_once __DIR__ . '/../services/ProfessorService.class.php';
require_once __DIR__ . '/../services/StudentService.class.php';

Flight::set('professor_service', new ProfessorService());

Flight::group('/professors', function () {
    /**
     * @OA\Get(
     *      path="/professors/all",
     *      tags={"professors"},
     *      summary="Get all professors",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all professors in the databases"
     *      )
     * )
     */

    Flight::route('GET /all', function() {
        
        // Count query
        $data = Flight::get('professor_service')->get_all_professors();
    
        // Response
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/professors/professor",
     *      tags={"professors"},
     *      summary="Get professor by id",
     *      @OA\Response(
     *           response=200,
     *           description="professor data or false if professor doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="professor ID")
     * )
     */

    Flight::route('GET /professor', function() {
        $params = Flight::request()->query;
        $professor = Flight::get('professor_service')->get_professor_by_id($params['id']);
        Flight::json($professor, 200);
    });


    /**
     * @OA\Get(
     *      path="/professors/get/{id}",
     *      tags={"professors"},
     *      summary="Get professor by id",
     *      @OA\Response(
     *           response=200,
     *           description="professor data or false if professor doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="professor ID")
     * )
     */

    Flight::route('GET /get/@id', function($id) {
        $professor = Flight::get('professor_service')->get_professor_by_id($id);
        Flight::json($professor, 200);
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
        $data = Flight::get('professor_service')->get_professors_paginated(
            $params['start'],
            $params['limit'],
            $params['search'],
            $params['order_column'],
            $params['order_direction']
        );
    
        foreach($data['data'] as $id => $professor) {
            $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                                '<div class="btn-group" role="group" aria-label="Actions">' .
                                                    '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="ProfessorService.open_edit_professor_modal('. $professor['id'] .')">Edit</button>' .
                                                    '<button type="button" class="btn btn-outline-danger" onClick="ProfessorService.delete_professor('. $professor['id'] .')">Delete</button>' .
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
     *      path="/professors/add",
     *      tags={"professors"},
     *      summary="Add professor data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="professor data, or exception if professor is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="professor data payload",
     *          @OA\JsonContent(
     *              required={"first_name","last_name","faculty","department", "salary"},
     *              @OA\Property(property="id", type="string", example="1", description="professor ID"),
     *              @OA\Property(property="first_name", type="string", example="Some professor name", description="professor name"),
     *              @OA\Property(property="last_name", type="string", example="Some professor last name", description="professor last name"),
     *              @OA\Property(property="faculty", type="string", example="Some faculty", description="professor faculty"),
     *              @OA\Property(property="department", type="string", example="Some department", description="professor department"),
     *              @OA\Property(property="salary", type="number", example="Some professor salary", description="professor salary"),
     *              @OA\Property(property="isAdmin", type="string", example="Some hashed password", description="is professor admin"),
     *              @OA\Property(property="password", type="number", example="Some password", description="professor password"),
     *          )
     *      )
     * )
     */

    Flight::route('POST /add', function() {
        $payload = Flight::request()->data->getData();
    
        if($payload['first_name'] == NULL || $payload['first_name'] == '') {
            Flight::halt(500, "First name field is missing!");
        }
    
        if($payload['id'] != null && $payload['id'] != '') {
            $professor = Flight::get('professor_service')->edit_professor($payload);
        } else {
            unset($payload['id']);
            $professor = Flight::get('professor_service')->add_professor($payload);
        }
    
        Flight::json(['message' => "You have succesfully added the professor", 'data' => $professor]);
    });

    /**
     * @OA\Delete(
     *      path="/professors/delete/{id}",
     *      tags={"professors"},
     *      summary="Delete professor by id",
     *      @OA\Response(
     *           response=200,
     *           description="Delete professor data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="professor ID")
     * )
     */
    Flight::route('DELETE /delete/@professor_id', function($professor_id) {
        if($professor_id == NULL || $professor_id == '') {
            Flight::halt(500, "You have to provide valid professor ID");
        }
       
        Flight::get('professor_service') -> delete_professor_by_id($professor_id);
    
        Flight::json(['message' => "You have succesfully deleted the professor"], 200);
    }); 
    
    /**
     * @OA\Get(
     *      path="/professors/{id}",
     *      tags={"professors"},
     *      summary="Get professor by id",
     *      @OA\Response(
     *           response=200,
     *           description="professor data or false if professor doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="professor ID")
     * )
     */
    
    Flight::route('GET /@professor_id', function($professor_id) {
        
        $professor = Flight::get('professor_service') -> get_professor_by_id($professor_id);
    
        Flight::json($professor, 200);
    });
});


