<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Wishlist.php';

$database = new Database();
$db = $database->getConnection();
$wishlist = new Wishlist($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($method) {
    case 'POST':
        // Add to wishlist
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->user_id) && isset($data->book_id)) {
            $wishlist->user_id = $data->user_id;
            $wishlist->book_id = $data->book_id;
            
            if($wishlist->add()) {
                http_response_code(201);
                echo json_encode(array("message" => "Book added to wishlist"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to add to wishlist"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data"));
        }
        break;
        
    case 'GET':
        if($action === 'check' && isset($_GET['user_id']) && isset($_GET['book_id'])) {
            // Check if book is in wishlist
            $wishlist->user_id = $_GET['user_id'];
            $wishlist->book_id = $_GET['book_id'];
            
            $isInWishlist = $wishlist->isInWishlist();
            http_response_code(200);
            echo json_encode(array("in_wishlist" => $isInWishlist));
        }
        else if($action === 'count' && isset($_GET['user_id'])) {
            // Get wishlist count
            $wishlist->user_id = $_GET['user_id'];
            $count = $wishlist->getCount();
            
            http_response_code(200);
            echo json_encode(array("count" => $count));
        }
        else if(isset($_GET['user_id'])) {
            // Get user wishlist
            $wishlist->user_id = $_GET['user_id'];
            $stmt = $wishlist->getUserWishlist();
            $items = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($items, $row);
            }
            
            http_response_code(200);
            echo json_encode($items);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "User ID required"));
        }
        break;
        
    case 'DELETE':
        if($action === 'clear' && isset($_GET['user_id'])) {
            // Clear entire wishlist
            $wishlist->user_id = $_GET['user_id'];
            
            if($wishlist->clearWishlist()) {
                http_response_code(200);
                echo json_encode(array("message" => "Wishlist cleared"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to clear wishlist"));
            }
        }
        else if(isset($_GET['user_id']) && isset($_GET['book_id'])) {
            // Remove from wishlist
            $wishlist->user_id = $_GET['user_id'];
            $wishlist->book_id = $_GET['book_id'];
            
            if($wishlist->remove()) {
                http_response_code(200);
                echo json_encode(array("message" => "Removed from wishlist"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to remove from wishlist"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "User ID and Book ID required"));
        }
        break;
        
    case 'OPTIONS':
        http_response_code(200);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
        break;
}
?>
