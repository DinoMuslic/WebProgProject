<?php
require_once __DIR__ . '/../services/StudentService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('student_service', new StudentService());

Flight::group('/students', function () {
    /**
     * @OA\Get(
     *      path="/students/all",
     *      tags={"students"},
     *      summary="Get all students",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all students in the databases"
     *      )
     * )
     */

    Flight::route('GET /all', function() {
        
        // Count query
        $data = Flight::get('student_service')->get_all_students();
    
        // Response
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/students/student",
     *      tags={"students"},
     *      summary="Get student by id",
     *      @OA\Response(
     *           response=200,
     *           description="student data or false if student doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="student ID")
     * )
     */

    Flight::route('GET /student', function() {
        $params = Flight::request()->query;
        $student = Flight::get('student_service')->get_student_by_id($params['id']);
        Flight::json($student, 200);
    });


    /**
     * @OA\Get(
     *      path="/students/get/{id}",
     *      tags={"students"},
     *      summary="Get student by id",
     *      @OA\Response(
     *           response=200,
     *           description="student data or false if student doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="student ID")
     * )
     */

    Flight::route('GET /get/@id', function($id) {
        $student = Flight::get('student_service')->get_student_by_id($id);
        Flight::json($student, 200);
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
        $data = Flight::get('student_service')->get_students_paginated(
            $params['start'],
            $params['limit'],
            $params['search'],
            $params['order_column'],
            $params['order_direction']
        );
    
        foreach($data['data'] as $id => $student) {
            $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                                '<div class="btn-group" role="group" aria-label="Actions">' .
                                                    '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="StudentService.open_edit_student_modal('. $student['id'] .')">Edit</button>' .
                                                    '<button type="button" class="btn btn-outline-danger" onClick="StudentService.delete_student('. $student['id'] .')">Delete</button>' .
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
     *      path="/students/add",
     *      tags={"students"},
     *      summary="Add student data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="student data, or exception if student is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="student data payload",
     *          @OA\JsonContent(
     *              required={"first_name","last_name", "email"},
     *              @OA\Property(property="id", type="string", example="1", description="student ID"),
     *              @OA\Property(property="first_name", type="string", example="Some student name", description="student name"),
     *              @OA\Property(property="last_name", type="string", example="Some student last name", description="student last name"),
     *              @OA\Property(property="email", type="string", example="Some student email", description="student email"),
     *              @OA\Property(property="faculty", type="string", example="Some faculty", description="student faculty"),
     *              @OA\Property(property="department", type="string", example="Some department", description="student department"),
     *              @OA\Property(property="enrolment_year", type="number", example="Some enrolment year", description="student enrolment year"),
     *              @OA\Property(property="password", type="string", example="Some hashed password", description="student password"),
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
            $student = Flight::get('student_service')->edit_student($payload);
        } else {
            unset($payload['id']);
            $student = Flight::get('student_service')->add_student($payload);
        }
    
        Flight::json(['message' => "You have succesfully added the student", 'data' => $student]);
    });

    /**
     * @OA\Delete(
     *      path="/students/delete/{id}",
     *      tags={"students"},
     *      summary="Delete student by id",
     *      @OA\Response(
     *           response=200,
     *           description="Delete student data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="student ID")
     * )
     */
    Flight::route('DELETE /delete/@student_id', function($student_id) {
        if($student_id == NULL || $student_id == '') {
            Flight::halt(500, "You have to provide valid student ID");
        }
       
        Flight::get('student_service') -> delete_student_by_id($student_id);
    
        Flight::json(['message' => "You have succesfully deleted the student"], 200);
    }); 
    
    /**
     * @OA\Get(
     *      path="/students/{id}",
     *      tags={"students"},
     *      summary="Get student by id",
     *      @OA\Response(
     *           response=200,
     *           description="student data or false if student doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="student ID")
     * )
     */
    
    Flight::route('GET /@student_id', function($student_id) {
        
        $student = Flight::get('student_service') -> get_student_by_id($student_id);
    
        Flight::json($student, 200);
    });

    /**
     * @OA\Get(
     *      path="/students/info",
     *      tags={"students"},
     *      summary="Get logged in student information",
     *      security={
     *         {"ApiKey": {}}
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="student data or false if student doesn't exist in the database"
     *      )
     * )
     */

     Flight::route('GET /info', function() {
        Flight::json(Flight::get('user'), 200);
    });
});


