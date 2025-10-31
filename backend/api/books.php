<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            // Get single book
            $book->id = $_GET['id'];
            if($book->readOne()) {
                $book_arr = array(
                    "id" => $book->id,
                    "title" => $book->title,
                    "author" => $book->author,
                    "description" => $book->description,
                    "price" => $book->price,
                    "image_url" => $book->image_url,
                    "category" => $book->category,
                    "stock_quantity" => $book->stock_quantity
                );
                http_response_code(200);
                echo json_encode($book_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Book not found."));
            }
        }
        elseif(isset($_GET['search'])) {
            // Search books
            $keywords = $_GET['search'];
            $stmt = $book->search($keywords);
            $books_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($books_arr, $row);
            }
            
            http_response_code(200);
            echo json_encode($books_arr);
        }
        elseif(isset($_GET['category'])) {
            // Get books by category
            $category = $_GET['category'];
            $stmt = $book->getByCategory($category);
            $books_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($books_arr, $row);
            }
            
            http_response_code(200);
            echo json_encode($books_arr);
        }
        else {
            // Get all books
            $stmt = $book->read();
            $books_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($books_arr, $row);
            }
            
            http_response_code(200);
            echo json_encode($books_arr);
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