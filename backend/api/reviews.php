<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Review.php';

$database = new Database();
$db = $database->getConnection();
$review = new Review($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($method) {
    case 'POST':
        // Create or update review
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->book_id) && isset($data->user_id) && isset($data->rating)) {
            $review->book_id = $data->book_id;
            $review->user_id = $data->user_id;
            $review->rating = $data->rating;
            $review->review_text = isset($data->review_text) ? $data->review_text : '';
            
            // Validate rating
            if($review->rating < 1 || $review->rating > 5) {
                http_response_code(400);
                echo json_encode(array("message" => "Rating must be between 1 and 5"));
                exit();
            }
            
            if($review->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Review submitted successfully"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to submit review"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data"));
        }
        break;
        
    case 'GET':
        if($action === 'user' && isset($_GET['book_id']) && isset($_GET['user_id'])) {
            // Get user's review for a book
            $review->book_id = $_GET['book_id'];
            $review->user_id = $_GET['user_id'];
            
            $userReview = $review->getUserReview();
            
            if($userReview) {
                http_response_code(200);
                echo json_encode($userReview);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Review not found"));
            }
        }
        else if($action === 'stats' && isset($_GET['book_id'])) {
            // Get rating statistics for a book
            $review->book_id = $_GET['book_id'];
            $stats = $review->getRatingStats();
            
            http_response_code(200);
            echo json_encode($stats);
        }
        else if(isset($_GET['book_id'])) {
            // Get all reviews for a book
            $review->book_id = $_GET['book_id'];
            $stmt = $review->getBookReviews();
            $reviews = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($reviews, $row);
            }
            
            http_response_code(200);
            echo json_encode($reviews);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Book ID required"));
        }
        break;
        
    case 'DELETE':
        // Delete review
        if(isset($_GET['book_id']) && isset($_GET['user_id'])) {
            $review->book_id = $_GET['book_id'];
            $review->user_id = $_GET['user_id'];
            
            if($review->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Review deleted successfully"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete review"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Book ID and User ID required"));
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
