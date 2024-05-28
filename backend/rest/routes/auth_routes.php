<?php

require_once __DIR__ . '/../services/AuthService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('auth_service', new AuthService());

Flight::group('/auth', function() {
    
    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="user data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="example@example.com", description="user email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="user password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        $payload = Flight::request()->data->getData();

        // Retrieve user by email (from either students or professors table)
        $user = Flight::get('auth_service')->get_user_by_email($payload['email']);

        // Validate user existence and password
        if(!$user || !password_verify($payload['password'], $user['password'])) {
            Flight::halt(500, "Invalid username or password");
        }

        // Remove password from the user data before sending it in the response
        unset($user['password']);
        
        // Prepare the JWT payload
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // token valid for one day
        ];

        // Generate JWT token
        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        // Respond with user data and JWT token
        Flight::json(
            array_merge($user, ['token' => $token])
        );
    });

    /**
     * @OA\Post(
     *      path="/auth/logout",
     *      tags={"auth"},
     *      summary="Logout from the system",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception if unable to verify jwt token"
     *      ),
     * )
     */
    Flight::route('POST /logout', function() {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token) {
                Flight::halt(401, "Missing authentication header");
            }

            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

            Flight::json([
                'jwt_decoded' => $decoded_token,
                'user' => $decoded_token->user
            ]);
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });
});