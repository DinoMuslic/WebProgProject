<?php
require_once __DIR__ . '/../services/CourseService.class.php';

Flight::set('course_service', new CourseService());

Flight::group('/courses', function () {
    /**
     * @OA\Get(
     *      path="/courses/all",
     *      tags={"courses"},
     *      summary="Get all courses",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all courses in the databases"
     *      )
     * )
     */

    Flight::route('GET /all', function() {
        
        // Count query
        $data = Flight::get('course_service')->get_all_courses();
    
        // Response
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/courses/course",
     *      tags={"courses"},
     *      summary="Get course by id",
     *      @OA\Response(
     *           response=200,
     *           description="Course data or false if course doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="Course ID")
     * )
     */

    Flight::route('GET /course', function() {
        $params = Flight::request()->query;
        $course = Flight::get('course_service')->get_course_by_id($params['id']);
        Flight::json($course, 200);
    });


    /**
     * @OA\Get(
     *      path="/courses/get/{id}",
     *      tags={"courses"},
     *      summary="Get course by id",
     *      @OA\Response(
     *           response=200,
     *           description="Course data or false if course doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Course ID")
     * )
     */

    Flight::route('GET /get/@id', function($id) {
        $course = Flight::get('course_service')->get_course_by_id($id);
        Flight::json($course, 200);
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
        $data = Flight::get('course_service')->get_courses_paginated(
            $params['start'],
            $params['limit'],
            $params['search'],
            $params['order_column'],
            $params['order_direction']
        );
    
        foreach($data['data'] as $id => $course) {
            $data['data'][$id]['action'] = '<div class="d-flex justify-content-center"' . 
                                                '<div class="btn-group" role="group" aria-label="Actions">' .
                                                    '<button style="margin-right: 10px;" type="button" class="btn btn-outline-primary" onClick="CourseService.open_edit_course_modal('. $course['id'] .')">Edit</button>' .
                                                    '<button type="button" class="btn btn-outline-danger" onClick="CourseService.delete_course('. $course['id'] .')">Delete</button>' .
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
     *      path="/courses/add",
     *      tags={"courses"},
     *      summary="Add Course data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="Course data, or exception if course is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="Course data payload",
     *          @OA\JsonContent(
     *              required={"title","faculty","department"},
     *              @OA\Property(property="id", type="string", example="1", description="Course ID"),
     *              @OA\Property(property="title", type="string", example="Some course title", description="Course title"),
     *              @OA\Property(property="faculty", type="string", example="Some faculty", description="Course faculty"),
     *              @OA\Property(property="department", type="string", example="Some department", description="Course department"),
     *              @OA\Property(property="professor", type="string", example="Some professor", description="Course professor"),
     *              @OA\Property(property="image", type="string", example="Some image", description="Course image"),
     *          )
     *      )
     * )
     */

    Flight::route('POST /add', function() {
        $payload = Flight::request()->data->getData();
    
        if($payload['title'] == NULL || $payload['title'] == '') {
            Flight::halt(500, "Title field is missing!");
        }
    
        if($payload['id'] != null && $payload['id'] != '') {
            $course = Flight::get('course_service')->edit_course($payload);
        } else {
            unset($payload['id']);
            $course = Flight::get('course_service')->add_course($payload);
        }
    
        Flight::json(['message' => "You have succesfully added the course", 'data' => $course]);
    });

    /**
     * @OA\Delete(
     *      path="/courses/delete/{id}",
     *      tags={"courses"},
     *      summary="Delete course by id",
     *      @OA\Response(
     *           response=200,
     *           description="Delete course data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Course ID")
     * )
     */
    Flight::route('DELETE /delete/@course_id', function($course_id) {
        if($course_id == NULL || $course_id == '') {
            Flight::halt(500, "You have to provide valid course ID");
        }
       
        Flight::get('course_service') -> delete_course_by_id($course_id);
    
        Flight::json(['message' => "You have succesfully deleted the course"], 200);
    }); 
    
    /**
     * @OA\Get(
     *      path="/courses/{id}",
     *      tags={"courses"},
     *      summary="Get course by id",
     *      @OA\Response(
     *           response=200,
     *           description="Course data or false if course doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="Course ID")
     * )
     */
    
    Flight::route('GET /@course_id', function($course_id) {
        
        $course = Flight::get('course_service') -> get_course_by_id($course_id);
    
        Flight::json($course, 200);
    });
});

