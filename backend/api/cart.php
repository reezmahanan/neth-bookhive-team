<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Cart.php';

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch($method) {
    case 'GET':
        if(isset($_GET['user_id'])) {
            $cart->user_id = $_GET['user_id'];
            $stmt = $cart->getUserCart();
            $cart_items = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($cart_items, $row);
            }
            
            http_response_code(200);
            echo json_encode($cart_items);
        }
        break;
        
    case 'POST':
        if(isset($data->user_id) && isset($data->book_id) && isset($data->quantity)) {
            $cart->user_id = $data->user_id;
            $cart->book_id = $data->book_id;
            $cart->quantity = $data->quantity;
            
            if($cart->addToCart()) {
                http_response_code(201);
                echo json_encode(array("message" => "Book added to cart."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to add book to cart."));
            }
        }
        break;
        
    case 'PUT':
        if(isset($data->user_id) && isset($data->book_id) && isset($data->quantity)) {
            $cart->user_id = $data->user_id;
            $cart->book_id = $data->book_id;
            $cart->quantity = $data->quantity;
            
            if($cart->updateQuantity()) {
                http_response_code(200);
                echo json_encode(array("message" => "Cart updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update cart."));
            }
        }
        break;
        
    case 'DELETE':
        if(isset($data->user_id) && isset($data->book_id)) {
            $cart->user_id = $data->user_id;
            $cart->book_id = $data->book_id;
            
            if($cart->removeFromCart()) {
                http_response_code(200);
                echo json_encode(array("message" => "Book removed from cart."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to remove book from cart."));
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