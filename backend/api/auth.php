<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->action)) {
            if($data->action == 'register') {
                // Register user
                $user->name = $data->name;
                $user->email = $data->email;
                $user->password = $data->password;
                
                if($user->register()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "User registered successfully."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to register user."));
                }
            }
            elseif($data->action == 'login') {
                // Login user
                $user->email = $data->email;
                $user->password = $data->password;
                
                if($user->login()) {
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Login successful.",
                        "user" => array(
                            "id" => $user->id,
                            "name" => $user->name,
                            "email" => $user->email
                        )
                    ));
                } else {
                    http_response_code(401);
                    echo json_encode(array("message" => "Login failed."));
                }
            }
        }
        break;
        
    case 'OPTIONS':
        http_response_code(200);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>