<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'rest/routes/course_routes.php';
require 'rest/routes/student_routes.php';
require 'rest/routes/professor_routes.php';
require 'rest/routes/book_routes.php';
require 'rest/routes/material_routes.php';
require 'rest/routes/auth_routes.php';
require 'rest/routes/middleware_routes.php';

Flight::start();