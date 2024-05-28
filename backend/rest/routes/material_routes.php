<?php
require_once __DIR__ . '/../services/MaterialService.class.php';

Flight::set('material_service', new MaterialService());

Flight::group('/materials', function () {
    /**
     * @OA\Get(
     *      path="/materials/all",
     *      tags={"materials"},
     *      summary="Get all materials",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all materials in the databases"
     *      )
     * )
     */

    Flight::route('GET /all', function() {
        
        // Count query
        $data = Flight::get('material_service')->get_all_materials();
    
        // Response
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/materials/material",
     *      tags={"materials"},
     *      summary="Get material by id",
     *      @OA\Response(
     *           response=200,
     *           description="material data or false if material doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="id", example="1", description="material ID")
     * )
     */

    Flight::route('GET /material', function() {
        $params = Flight::request()->query;
        $material = Flight::get('material_service')->get_material_by_id($params['id']);
        Flight::json($material, 200);
    });


    /**
     * @OA\Get(
     *      path="/materials/get/{id}",
     *      tags={"materials"},
     *      summary="Get material by id",
     *      @OA\Response(
     *           response=200,
     *           description="material data or false if material doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="material ID")
     * )
     */

    Flight::route('GET /get/@id', function($id) {
        $material = Flight::get('material_service')->get_material_by_id($id);
        Flight::json($material, 200);
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
        $data = Flight::get('material_service')->get_materials_paginated(
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
     *      path="/materials/add",
     *      tags={"materials"},
     *      summary="Add material data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="material data, or exception if material is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="material data payload",
     *          @OA\JsonContent(
     *              required={"id", "title"},
     *              @OA\Property(property="id", type="string", example="1", description="material ID"),
     *              @OA\Property(property="course_id", type="string", example="2", description="material course ID"),
     *              @OA\Property(property="title", type="string", example="Some material title", description="material title"),
     *              @OA\Property(property="contents", type="string", example="Some content", description="material content"),
     *              @OA\Property(property="announcements", type="string", example="Some announcement", description="material announcement"),
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
            $material = Flight::get('material_service')->edit_material($payload);
        } else {
            unset($payload['id']);
            $material = Flight::get('material_service')->add_material($payload);
        }
    
        Flight::json(['message' => "You have succesfully added the material", 'data' => $material]);
    });

    /**
     * @OA\Delete(
     *      path="/materials/delete/{id}",
     *      tags={"materials"},
     *      summary="Delete material by id",
     *      @OA\Response(
     *           response=200,
     *           description="Delete material data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="material ID")
     * )
     */
    Flight::route('DELETE /delete/@material_id', function($material_id) {
        if($material_id == NULL || $material_id == '') {
            Flight::halt(500, "You have to provide valid material ID");
        }
       
        Flight::get('material_service') -> delete_material_by_id($material_id);
    
        Flight::json(['message' => "You have succesfully deleted the material"], 200);
    });


    

    /**
     * @OA\Delete(
     *      path="/materials/delete/course/{course_id}",
     *      tags={"materials"},
     *      summary="Delete materials by course ID",
     *      @OA\Response(
     *           response=200,
     *           description="Delete material data or exception"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="string"), in="path", name="course_id", example="course_101", description="Course ID")
     * )
     */
    Flight::route('DELETE /delete/course/@course_id', function($course_id) {
        if($course_id == NULL || $course_id == '') {
            Flight::halt(500, "You have to provide a valid course ID");
        }
    
        Flight::get('material_service')->delete_materials_by_course_id($course_id);

        Flight::json(['message' => "You have successfully deleted the materials for the course"], 200);
    });

    
    /**
     * @OA\Get(
     *      path="/materials/{id}",
     *      tags={"materials"},
     *      summary="Get material by id",
     *      @OA\Response(
     *           response=200,
     *           description="material data or false if material doesn't exist in the database"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="id", example="1", description="material ID")
     * )
     */
    
    Flight::route('GET /@material_id', function($material_id) {
        
        $material = Flight::get('material_service') -> get_material_by_id($material_id);
    
        Flight::json($material, 200);
    });

    /**
     * @OA\Get(
     *      path="/materials/course/{course_id}",
     *      tags={"materials"},
     *      summary="Get all materials by course_id",
     *      @OA\Response(
     *           response=200,
     *           description="Array of materials or empty array if none found"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="string"), in="path", name="course_id", example="course_101", description="Course ID")
     * )
     */
    Flight::route('GET /course/@course_id', function($course_id) {
        $materials = Flight::get('material_service')->get_all_materials_by_course_id($course_id);
        Flight::json($materials, 200);
    });
});

