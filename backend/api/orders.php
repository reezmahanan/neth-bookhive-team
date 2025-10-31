<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Order.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->user_id) && isset($data->total_amount)) {
            $order->user_id = $data->user_id;
            $order->total_amount = $data->total_amount;
            $order->status = 'pending';
            
            if($order->create()) {
                // Add order items
                if(isset($data->items) && is_array($data->items)) {
                    foreach($data->items as $item) {
                        $order->addItem($order->id, $item->book_id, $item->quantity, $item->price);
                    }
                }
                
                http_response_code(201);
                echo json_encode(array(
                    "message" => "Order created successfully.",
                    "order_id" => $order->id
                ));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create order."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
        break;
        
    case 'GET':
        if(isset($_GET['user_id'])) {
            $order->user_id = $_GET['user_id'];
            $stmt = $order->getUserOrders();
            $orders = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($orders, $row);
            }
            
            http_response_code(200);
            echo json_encode($orders);
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