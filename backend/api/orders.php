<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Order.php';
include_once '../models/Cart.php';
include_once '../helpers/PaymentHelper.php';
include_once '../helpers/EmailHelper.php';
include_once '../helpers/SecurityHelper.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($method) {
    case 'POST':
        // Checkout endpoint
        if($action === 'checkout') {
            $data = json_decode(file_get_contents("php://input"));
            
            // Validate required fields
            if(!isset($data->cart_items) || !isset($data->shipping) || !isset($data->payment)) {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete checkout data"));
                exit();
            }

            // Validate cart items
            if(empty($data->cart_items)) {
                http_response_code(400);
                echo json_encode(array("message" => "Cart is empty"));
                exit();
            }

            try {
                // Start transaction
                $db->beginTransaction();

                // Calculate totals
                $subtotal = 0;
                foreach($data->cart_items as $item) {
                    $subtotal += $item->price * $item->quantity;
                }
                
                $shipping_cost = $subtotal >= 1000 ? 0 : 150;
                $tax = $subtotal * 0.10;
                $total_amount = $subtotal + $shipping_cost + $tax;

                // Set order data
                $order->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                $order->total_amount = $total_amount;
                $order->subtotal = $subtotal;
                $order->shipping_cost = $shipping_cost;
                $order->tax = $tax;
                $order->status = 'pending';
                $order->payment_status = 'pending';
                $order->payment_method = $data->payment->method;
                $order->payment_id = null;
                $order->notes = isset($data->notes) ? $data->notes : '';

                // Create order
                if(!$order->create()) {
                    throw new Exception("Failed to create order");
                }

                // Add order items
                foreach($data->cart_items as $item) {
                    if(!$order->addItem($order->id, $item->book_id, $item->quantity, $item->price)) {
                        throw new Exception("Failed to add order item");
                    }
                }

                // Add shipping address
                $shipping_data = array(
                    'full_name' => SecurityHelper::sanitizeInput($data->shipping->fullName),
                    'email' => SecurityHelper::sanitizeInput($data->shipping->email),
                    'phone' => SecurityHelper::sanitizeInput($data->shipping->phone),
                    'address_line1' => SecurityHelper::sanitizeInput($data->shipping->address1),
                    'address_line2' => SecurityHelper::sanitizeInput($data->shipping->address2),
                    'city' => SecurityHelper::sanitizeInput($data->shipping->city),
                    'state' => SecurityHelper::sanitizeInput($data->shipping->state),
                    'postal_code' => SecurityHelper::sanitizeInput($data->shipping->postalCode),
                    'country' => SecurityHelper::sanitizeInput($data->shipping->country)
                );

                if(!$order->addShippingAddress($order->id, $shipping_data)) {
                    throw new Exception("Failed to add shipping address");
                }

                // Process payment
                $payment_result = null;
                if($data->payment->method === 'card') {
                    // For testing, use mock payment. In production, use real Stripe
                    // $payment_result = PaymentHelper::createPaymentIntent($total_amount, ['order_id' => $order->order_number]);
                    $payment_result = PaymentHelper::mockPayment($total_amount, ['order_id' => $order->order_number]);
                    
                    if($payment_result['success']) {
                        $order->payment_id = $payment_result['payment_intent_id'];
                        $order->updatePaymentStatus($order->id, 'completed', $payment_result['payment_intent_id']);
                        $order->updateStatus($order->id, 'processing', 'Payment completed successfully');
                    } else {
                        throw new Exception($payment_result['error']);
                    }
                } else {
                    // Cash on delivery
                    $order->updateStatus($order->id, 'processing', 'Order confirmed - Cash on Delivery');
                }

                // Clear cart
                $cart = new Cart($db);
                if(isset($_SESSION['user_id'])) {
                    $cart->user_id = $_SESSION['user_id'];
                } else {
                    $cart->session_id = session_id();
                }
                $cart->clearCart();

                // Commit transaction
                $db->commit();

                // Get complete order details for email
                $order_details = $order->getOrderById($order->id);
                $order_items = $order->getOrderItems($order->id);

                // Send confirmation email
                EmailHelper::sendOrderConfirmation($order_details, $order_items);

                http_response_code(201);
                echo json_encode(array(
                    "success" => true,
                    "message" => "Order placed successfully",
                    "order_id" => $order->id,
                    "order_number" => $order->order_number,
                    "payment_result" => $payment_result
                ));

            } catch (Exception $e) {
                // Rollback transaction
                $db->rollBack();
                
                http_response_code(500);
                echo json_encode(array(
                    "success" => false,
                    "message" => "Order processing failed: " . $e->getMessage()
                ));
            }
            exit();
        }

        // Create order (old endpoint for backward compatibility)
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->user_id) && isset($data->total_amount)) {
            $order->user_id = $data->user_id;
            $order->total_amount = $data->total_amount;
            $order->subtotal = isset($data->subtotal) ? $data->subtotal : $data->total_amount;
            $order->shipping_cost = isset($data->shipping_cost) ? $data->shipping_cost : 0;
            $order->tax = isset($data->tax) ? $data->tax : 0;
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = isset($data->payment_method) ? $data->payment_method : 'cod';
            $order->payment_id = null;
            $order->notes = isset($data->notes) ? $data->notes : '';
            
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
                    "order_id" => $order->id,
                    "order_number" => $order->order_number
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
        // Get order by ID with full details
        if($action === 'details' && isset($_GET['order_id'])) {
            $order_details = $order->getOrderById($_GET['order_id']);
            
            if($order_details) {
                $order_details['items'] = $order->getOrderItems($_GET['order_id']);
                $order_details['status_history'] = $order->getStatusHistory($_GET['order_id']);
                
                http_response_code(200);
                echo json_encode($order_details);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Order not found"));
            }
        }
        // Get order by order number
        else if($action === 'details' && isset($_GET['order_number'])) {
            $order_details = $order->getOrderByNumber($_GET['order_number']);
            
            if($order_details) {
                $order_details['items'] = $order->getOrderItems($order_details['id']);
                $order_details['status_history'] = $order->getStatusHistory($order_details['id']);
                
                http_response_code(200);
                echo json_encode($order_details);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Order not found"));
            }
        }
        // Get user orders
        else if(isset($_GET['user_id'])) {
            $order->user_id = $_GET['user_id'];
            $stmt = $order->getUserOrders();
            $orders = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($orders, $row);
            }
            
            http_response_code(200);
            echo json_encode($orders);
        }
        else {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required parameters"));
        }
        break;

    case 'PUT':
        // Update order status
        if($action === 'status' && isset($_GET['order_id'])) {
            $data = json_decode(file_get_contents("php://input"));
            
            if(isset($data->status)) {
                $notes = isset($data->notes) ? $data->notes : '';
                
                if($order->updateStatus($_GET['order_id'], $data->status, $notes)) {
                    // Send status update email
                    $order_details = $order->getOrderById($_GET['order_id']);
                    if($order_details) {
                        EmailHelper::sendStatusUpdate($order_details, $data->status);
                        
                        // If shipped, send shipping confirmation
                        if($data->status === 'shipped') {
                            $tracking = isset($data->tracking_number) ? $data->tracking_number : '';
                            EmailHelper::sendShippingConfirmation($order_details, $tracking);
                        }
                    }
                    
                    http_response_code(200);
                    echo json_encode(array("message" => "Order status updated successfully"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Failed to update order status"));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Status required"));
            }
        }
        // Update payment status
        else if($action === 'payment' && isset($_GET['order_id'])) {
            $data = json_decode(file_get_contents("php://input"));
            
            if(isset($data->payment_status)) {
                $payment_id = isset($data->payment_id) ? $data->payment_id : null;
                
                if($order->updatePaymentStatus($_GET['order_id'], $data->payment_status, $payment_id)) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Payment status updated successfully"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Failed to update payment status"));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Payment status required"));
            }
        }
        // Cancel order
        else if($action === 'cancel' && isset($_GET['order_id'])) {
            $data = json_decode(file_get_contents("php://input"));
            
            // Get order details first
            $order_details = $order->getOrderById($_GET['order_id']);
            
            if(!$order_details) {
                http_response_code(404);
                echo json_encode(array("message" => "Order not found"));
                exit();
            }
            
            // Check if order can be cancelled (only pending/processing orders)
            if($order_details['status'] !== 'pending' && $order_details['status'] !== 'processing') {
                http_response_code(400);
                echo json_encode(array("message" => "Cannot cancel order with status: " . $order_details['status']));
                exit();
            }
            
            $reason = isset($data->reason) ? $data->reason : 'Customer request';
            
            // Update order status to cancelled
            $query = "UPDATE orders 
                     SET status='cancelled', 
                         cancellation_reason=:reason,
                         cancelled_at=CURRENT_TIMESTAMP 
                     WHERE id=:order_id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":reason", $reason);
            $stmt->bindParam(":order_id", $_GET['order_id']);
            
            if($stmt->execute()) {
                // Add to status history
                $order->addStatusHistory($_GET['order_id'], 'cancelled', 'Order cancelled: ' . $reason);
                
                // Restore book stock
                $items = $order->getOrderItems($_GET['order_id']);
                foreach($items as $item) {
                    $query = "UPDATE books SET stock = stock + :quantity WHERE id = :book_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(":quantity", $item['quantity']);
                    $stmt->bindParam(":book_id", $item['book_id']);
                    $stmt->execute();
                }
                
                http_response_code(200);
                echo json_encode(array("message" => "Order cancelled successfully"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Failed to cancel order"));
            }
        }
        else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid request"));
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